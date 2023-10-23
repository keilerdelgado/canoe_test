<?php

class BaseController
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function index()
    {
        $data = $this->model->read();
        $this->jsonResponse($data);
    }

    public function read($id)
    {
        $data = $this->model->read($id);

        if (isset($data['error'])) {
            $this->jsonResponse(["error" => $data['error']], 400);
        } elseif (!$data) {
            $this->jsonResponse(["error" => "Data not found"], 404);
        } else {
            $this->jsonResponse($data[0]);
        }
    }

    public function store()
    {
        // Get the raw JSON data from the request body
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $res = $this->model->create($data);
        if ($res['error'] !== null) {
            $this->jsonResponse(["error" => $res['error']], 400);
        } else {
            $this->jsonResponse(["message" => "Data successfully stored", "id" => $res['id']]);
        }
    }

    public function update($id)
    {
        if ($id === null) {
            $this->jsonResponse(["error" => "Missing ID"], 400);
        }

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $res = $this->model->update($id, $data);

        if ($res['error'] !== null) {
            $this->jsonResponse(["error" => $res['error']], 400);
        } else {
            $this->jsonResponse(["message" => "Successfully updated " . $res['rows_affected'] . " records."], 201);
        }
    }

    public function destroy($id)
    {
        if ($id === null) {
            $this->jsonResponse(["error" => "Missing ID"], 400);
        }

        $res = $this->model->delete($id);

        if ($res['error'] !== null) {
            $this->jsonResponse(["error" => $res['error']], 400);
        } else {
            $this->jsonResponse(["message" => "Successfully deleted" . $res['rows_affected'] . " records."], 201);
        }
    }
}

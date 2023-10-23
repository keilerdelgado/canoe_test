<?php
require_once 'BaseController.php';
require_once 'models/FundsModel.php';
require_once 'services/RabbitmqHandler.php';

class FundsController extends BaseController
{
    public function __construct()
    {
        $this->model = new FundsModel();
    }

    public function index()
    {
        // Get query params from URL
        $query_params = [];
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $query_params);
        }

        if (empty($query_params)) {
            // if no query params, return all
            $data = $this->model->read();
        } else {
            // if query params, return filtered
            $data = $this->model->searchByAttributes($query_params);
        }
        $this->jsonResponse($data);
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

        $duplicates = $this->model->findDuplicates($data['name'], $data['manager_id']);
        if (count($duplicates) > 0) {
            $rabbitMQHandler = new RabbitmqHandler();
            $eventName = 'duplicate_fund_warning';
            $message_body = json_encode([
                'event' => $eventName,
                'existing' => $duplicates,
                "new_fund_id" => $res['id'],
                'data' => $data,
            ]);
            $rabbitMQHandler->publishMessage($message_body, QUEUE_NAME);
            $rabbitMQHandler->close();
        }
    }

    public function duplicates()
    {
        $potential_duplicates = [];
        $funds = $this->model->read(null, false);
        foreach ($funds as $fund) {
            $duplicates = $this->model->findDuplicates($fund['name'], $fund['manager_id']);
            if (count($duplicates) > 0) {
                $potential_duplicates[] = [
                    'fund' => $fund,
                    'duplicates' => $duplicates,
                ];
            }
        }
        $this->jsonResponse($potential_duplicates);
    }
}

<?php
require_once 'BaseController.php';
require_once 'models/FundsModel.php';

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
}

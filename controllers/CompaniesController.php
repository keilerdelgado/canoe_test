<?php
require_once 'BaseController.php';
require_once 'models/CompaniesModel.php';

class CompaniesController extends BaseController
{
    public function __construct()
    {
        $this->model = new CompaniesModel();
    }
}

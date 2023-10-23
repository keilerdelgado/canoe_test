<?php
require_once 'BaseController.php';
require_once 'models/ManagersModel.php';

class ManagersController extends BaseController
{
    public function __construct()
    {
        parent::__construct(new ManagersModel());
    }
}

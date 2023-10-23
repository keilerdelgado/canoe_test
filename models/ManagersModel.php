<?php
require_once 'BaseModel.php';

class ManagersModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'managers';
        $this->required_fields = ["company_name"];
    }
}

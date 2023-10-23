<?php
require_once 'BaseModel.php';

class CompaniesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'companies';
        $this->required_fields = ["name"];
    }
}

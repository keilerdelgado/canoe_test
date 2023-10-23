<?php
require_once 'BaseModel.php';

class AliasesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'aliases';
        $this->required_fields = ["alias", "fund_id"];
    }
}

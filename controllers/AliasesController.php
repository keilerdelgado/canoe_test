<?php
require_once 'BaseController.php';
require_once 'models/AliasesModel.php';

class AliasesController extends BaseController
{
    public function __construct()
    {
        parent::__construct(new AliasesModel());
    }
}

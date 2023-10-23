<?php
require_once 'BaseModel.php';

class FundsModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'funds';
        $this->required_fields = ["name", "start_year", "manager_id"];
    }

    public function searchByAttributes($attributes)
    {
        // @TODO validate attributes types and string case
        $fund_name = $attributes['name'] ?? null;
        $fund_manager = $attributes['manager_id'] ?? null;
        $fund_year = $attributes['start_year'] ?? null;

        $sql = "SELECT * FROM $this->table WHERE 1=1 ";

        $params = [];

        if (isset($fund_name)) {
            $sql .= "AND name LIKE ? ";
            $params[] = '%' . $fund_name . '%';
        }

        if (isset($fund_manager)) {
            $sql .= "AND manager_id = ? ";
            $params[] = $fund_manager;
        }

        if (isset($fund_year)) {
            $sql .= "AND start_year = ? ";
            $params[] = $fund_year;
        }

        echo $sql;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

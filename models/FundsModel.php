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

    public function read($id = null, $with_aliases = true)
    {
        try {
            if (isset($id)) {
                // If an ID is provided, retrieve the fund and, optionally, its aliases
                $sql = "SELECT funds.*";
                if ($with_aliases) {
                    $sql .= ", aliases.alias";
                }
                $sql .= " FROM $this->table";
                if ($with_aliases) {
                    $sql .= " LEFT JOIN aliases ON funds.id = aliases.fund_id";
                }
                $sql .= " WHERE funds.id = ?";
                $params = [$id];
            } else {
                // If no ID is provided, retrieve all funds and, optionally, their aliases
                $sql = "SELECT funds.*";
                if ($with_aliases) {
                    $sql .= ", aliases.alias";
                }
                $sql .= " FROM $this->table";
                if ($with_aliases) {
                    $sql .= " LEFT JOIN aliases ON funds.id = aliases.fund_id";
                }
                $params = [];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
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

    public function create($data)
    {
        // Validate required fields
        $missing_fields = [];
        foreach ($this->required_fields as $field) {
            if (!isset($data[$field])) {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            $error_message = "Missing fields: " . implode(', ', $missing_fields);
            return [
                'id' => null,
                'error' =>  $error_message,
            ];
        }

        try {
            $this->db->beginTransaction();

            // Insert data into the `funds` table
            $sql = "INSERT INTO funds (name, manager_id, start_year) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['name'], $data['manager_id'], $data['start_year']]);
            $fundId = $this->db->lastInsertId();

            // Insert aliases into the `aliases` table
            $aliasSql = "INSERT INTO aliases (alias, fund_id) VALUES (?, ?)";
            $aliasStmt = $this->db->prepare($aliasSql);

            foreach ($data['aliases'] as $alias) {
                $aliasStmt->execute([$alias, $fundId]);
            }

            $this->db->commit();
            return [
                'id' => $fundId,
                'error' => null,
            ];
        } catch (PDOException $e) {
            $this->db->rollBack();

            return [
                'id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function findDuplicates($name, $manager_id)
    {
        $sql = "SELECT DISTINCT funds.*, aliases.alias
            FROM funds
            LEFT JOIN aliases ON funds.id = aliases.fund_id
            WHERE (funds.name = ? OR aliases.alias = ?) AND funds.manager_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $name, $manager_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

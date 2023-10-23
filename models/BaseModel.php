<?php
require_once('config.php');

class BaseModel
{
    protected $db;
    protected $table;
    protected $required_fields = [];

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
    }

    public function create($data)
    {
        try {
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

            // Insert data into the table
            $columns = implode(', ', array_keys($data));
            $values = implode(', ', array_fill(0, count($data), '?'));

            $stmt = $this->db->prepare("INSERT INTO $this->table ($columns) VALUES ($values)");
            $stmt->execute(array_values($data));

            return [
                'id' => $this->db->lastInsertId(),
                'error' => null,
            ];
        } catch (PDOException $e) {
            return [
                'id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function read($id = null)
    {
        try {
            $sql = "SELECT * FROM $this->table";

            if (isset($id)) {
                $sql .= " WHERE id = ?";
                $params = [$id];
            } else {
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

    public function update($id, $data)
    {
        try {
            // Validate that at least one of the required fields is present
            $hasRequiredField = false;
            foreach ($this->required_fields as $field) {
                if (array_key_exists($field, $data)) {
                    $hasRequiredField = true;
                    break;
                }
            }

            if (!$hasRequiredField) {
                $error_message = "At least one of the following fields should be present: " . implode(', ', $this->required_fields) . ".";
                return [
                    'rows_affected' => 0,
                    'error' => $error_message,
                ];
            }

            $set = implode(' = ?, ', array_keys($data)) . ' = ?';

            $stmt = $this->db->prepare("UPDATE $this->table SET $set WHERE id = ?");
            $stmt->execute(array_merge(array_values($data), [$id]));

            return [
                'rows_affected' => $stmt->rowCount(),
                'error' => null,
            ];
        } catch (PDOException $e) {
            return [
                'rows_affected' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id = ?");
            $stmt->execute([$id]);

            return [
                'rows_affected' => $stmt->rowCount(),
                'error' => null,
            ];
        } catch (PDOException $e) {
            return [
                'rows_affected' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }
}

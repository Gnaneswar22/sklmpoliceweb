<?php
class HistoryTracker {
    private $conn;
    private $table_name;
    private $user_id;

    public function __construct($conn, $table_name, $user_id) {
        $this->conn = $conn;
        $this->table_name = $table_name;
        $this->user_id = $user_id;
    }

    public function trackChange($record_id, $action_type, $data_before = null, $data_after = null) {
        $sql = "INSERT INTO {$this->table_name}_history (
                    {$this->table_name}_id, 
                    action_type, 
                    data_before, 
                    data_after, 
                    created_by
                ) VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "isssi", 
            $record_id, 
            $action_type, 
            $data_before ? json_encode($data_before) : null,
            $data_after ? json_encode($data_after) : null,
            $this->user_id
        );
        
        return mysqli_stmt_execute($stmt);
    }

    public function undo($record_id) {
        // Get the latest history record
        $sql = "SELECT * FROM {$this->table_name}_history 
                WHERE {$this->table_name}_id = ? 
                ORDER BY created_at DESC LIMIT 1";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $record_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $history = mysqli_fetch_assoc($result);

        if ($history) {
            switch ($history['action_type']) {
                case 'CREATE':
                    // Delete the record
                    return $this->deleteRecord($record_id);
                case 'UPDATE':
                    // Restore previous data
                    return $this->restoreData($record_id, json_decode($history['data_before'], true));
                case 'DELETE':
                    // Restore the deleted record
                    return $this->restoreData($record_id, json_decode($history['data_before'], true));
            }
        }
        return false;
    }

    public function redo($record_id) {
        // Similar to undo but uses data_after instead of data_before
        $sql = "SELECT * FROM {$this->table_name}_history 
                WHERE {$this->table_name}_id = ? 
                ORDER BY created_at DESC LIMIT 1";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $record_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $history = mysqli_fetch_assoc($result);

        if ($history) {
            return $this->restoreData($record_id, json_decode($history['data_after'], true));
        }
        return false;
    }

    private function deleteRecord($record_id) {
        $sql = "DELETE FROM {$this->table_name} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $record_id);
        return mysqli_stmt_execute($stmt);
    }

    private function restoreData($record_id, $data) {
        if (!$data) return false;

        $fields = array_keys($data);
        $values = array_values($data);
        $types = str_repeat('s', count($fields));

        $sql = "UPDATE {$this->table_name} SET " . 
               implode(' = ?, ', $fields) . ' = ? ' .
               "WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        $values[] = $record_id;
        $types .= 'i';
        mysqli_stmt_bind_param($stmt, $types, ...$values);
        
        return mysqli_stmt_execute($stmt);
    }
}
?>

<?php
abstract class Module {
    protected $pdo;
    protected $table;
    protected $history_table;
    
    public function __construct($pdo, $table) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->history_table = $table . '_history';
        $this->createHistoryTable();
    }

    // Create history table for undo/redo operations
    private function createHistoryTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->history_table} (
            history_id INT AUTO_INCREMENT PRIMARY KEY,
            record_id INT NOT NULL,
            action ENUM('create', 'update', 'delete') NOT NULL,
            data JSON NOT NULL,
            user_id INT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    // CRUD Operations with History
    public function create($data) {
        try {
            $this->pdo->beginTransaction();
            
            // Insert new record
            $columns = implode(', ', array_keys($data));
            $values = implode(', ', array_fill(0, count($data), '?'));
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_values($data));
            $id = $this->pdo->lastInsertId();

            // Log action in history
            $this->logHistory($id, 'create', $data);
            
            $this->pdo->commit();
            return $id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function read($id = null) {
        if ($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return $this->pdo->query("SELECT * FROM {$this->table}")->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function update($id, $data) {
        try {
            $this->pdo->beginTransaction();
            
            // Store old data for history
            $old_data = $this->read($id);
            
            // Update record
            $set = implode('=?, ', array_keys($data)) . '=?';
            $sql = "UPDATE {$this->table} SET $set WHERE id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([...array_values($data), $id]);

            // Log action in history
            $this->logHistory($id, 'update', [
                'old' => $old_data,
                'new' => $data
            ]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        try {
            $this->pdo->beginTransaction();
            
            // Store data for history
            $old_data = $this->read($id);
            
            // Delete record
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);

            // Log action in history
            $this->logHistory($id, 'delete', $old_data);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Undo/Redo Operations
    public function undo($history_id) {
        try {
            $this->pdo->beginTransaction();
            
            // Get history record
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->history_table} WHERE history_id = ?");
            $stmt->execute([$history_id]);
            $history = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$history) {
                throw new Exception("History record not found");
            }

            switch ($history['action']) {
                case 'create':
                    // Undo create = delete
                    $this->delete($history['record_id']);
                    break;
                    
                case 'update':
                    // Undo update = restore old data
                    $old_data = json_decode($history['data'], true)['old'];
                    $this->update($history['record_id'], $old_data);
                    break;
                    
                case 'delete':
                    // Undo delete = recreate
                    $this->create(json_decode($history['data'], true));
                    break;
            }
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function logHistory($record_id, $action, $data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->history_table} 
            (record_id, action, data, user_id) 
            VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $record_id,
            $action,
            json_encode($data),
            $_SESSION['user_id']
        ]);
    }

    // Get history for a record
    public function getHistory($record_id) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->history_table} 
            WHERE record_id = ? 
            ORDER BY timestamp DESC"
        );
        $stmt->execute([$record_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<?php
class MySQLSessionHandler implements SessionHandlerInterface {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function open($savePath, $sessionName) {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT data FROM php_sessions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch()) {
            return $row['data'];
        }
        return '';
    }

    public function write($id, $data) {
        $stmt = $this->pdo->prepare("REPLACE INTO php_sessions (id, data) VALUES (:id, :data)");
        return $stmt->execute(['id' => $id, 'data' => $data]);
    }

    public function destroy($id) {
        $stmt = $this->pdo->prepare("DELETE FROM php_sessions WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function gc($maxlifetime) {
        $stmt = $this->pdo->prepare("DELETE FROM php_sessions WHERE timestamp < (NOW() - INTERVAL :lifetime SECOND)");
        return $stmt->execute(['lifetime' => $maxlifetime]);
    }
}
?>

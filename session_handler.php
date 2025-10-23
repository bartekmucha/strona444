<?php
class MySQLSessionHandler implements SessionHandlerInterface {
    private $pdo;

    public function __construct($host, $db, $user, $pass) {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("❌ Błąd połączenia z bazą: " . $e->getMessage());
        }
    }

    public function open($savePath, $sessionName) { return true; }
    public function close() { return true; }

    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT data FROM php_sessions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['data'] : '';
    }

    public function write($id, $data) {
        $stmt = $this->pdo->prepare("REPLACE INTO php_sessions (id, data, timestamp) VALUES (:id, :data, NOW())");
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

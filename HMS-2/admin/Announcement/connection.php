<?php
require_once(dirname(__DIR__, 2) . "/Includes/config.php");

class Connection {
    public PDO $pdo;

    public function __construct() {
        $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function getannouncements() {
        $statement = $this->pdo->prepare("SELECT * FROM announcement ORDER BY created_at DESC");
        $statement->execute();
        return $statement->fetchAll();
    }
}

return new Connection();

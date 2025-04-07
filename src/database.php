<?php

declare(strict_types=1);

class Database {
    public function __construct() {}
    private function connection () {
        $username = Config::loadConfig()["DB_USERNAME"];
        $host = Config::loadConfig()["DB_HOST"];
        $password = Config::loadConfig()["DB_PASSWORD"];
        $databaseName = Config::loadConfig()["DB_NAME"];
        $dsn = "mysql:host={$host};dbname={$databaseName};charset=utf8";
        try {
        $connection = new PDO($dsn, $username, $password, Config::loadConfig()["DB_OPTION"]);
        return $connection;
        } catch (PDOException $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function hasConnection (): bool {
        return $this->connection() ? true : false;
    }

    public function setBindedExecution (string $query, ?array $bindData = null) {
        $statement = $this->connection()->prepare($query);
        if (!empty($bindData)) {
            foreach ($bindData as $key => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $statement->bindValue(":$key", $value, $type);
            }
        }
        $statement->execute();
        return $statement;
    }
}
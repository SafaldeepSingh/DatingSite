<?php

abstract class Database2
{
    private const _serverName = "localhost";
    private const _port = 3307;
    private const _database = "ignite";
    private const _username = "project";
    private const _password = "project";
    private const _connectionString = "mysql:host=" . Database2::_serverName . ";dbname=" . self::_database . ";port=" . self::_port;

    private PDO $_connection;

    public function __construct()
    {
        try {
            $this->_connection = new PDO(self::_connectionString, self::_username, self::_password);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection Failed: {$exception->getMessage()}";
            die("Connection to DB Failed");
        }

    }


    protected function execute(string $query): array|false
    {
        $stmt = $this->_connection->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    protected function insertValues(string $query): int
    {
        $this->_connection->exec($query);
        return $this->_connection->lastInsertId();
    }




}

?>
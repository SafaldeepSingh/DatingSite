<?php

class Database
{
    private const _serverName = "localhost";
    private const _port = 3307;
    private const _database = "ignite";
    private const _username = "project";
    private const _password = "project";
    private const _connectionString = "mysql:host=" . Database::_serverName . ";dbname="
                                        . Database::_database . ";port=" . Database::_port;
    private static PDO $_connection;

    static function executeQuery(string $query,array $params = array()
        ,int $fetchMode = -1, bool $fetchAll = true
        ,int $fetchColumn = 0){
        self::init();
        $connection = Database::$_connection;
        $statment = $connection->prepare($query);
        $statmentExecuteStatus = $statment->execute($params);
        if($fetchMode==-1)
            return $statmentExecuteStatus;
        else if($fetchMode == PDO::FETCH_COLUMN)
            $statment->setFetchMode($fetchMode,$fetchColumn);
        else
        $statment->setFetchMode($fetchMode);
        return ($fetchAll?$statment->fetchAll():$statment->fetch());
    }
    private static function init(){
        try {
            Database::$_connection = new PDO(Database::_connectionString
                                ,Database::_username, Database::_password);
            Database::$_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //    echo "success <br />";
        } catch (PDOException $exception) {
            echo "Connection Failed: {$exception->getMessage()}";
            exit();
        }

    }
}
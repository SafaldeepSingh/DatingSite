<?php
    const serverName = "localhost";
    const port = 3307;
    const database = "ignite";
    const username = "project";
    const password = "project";
    const connectionString = "mysql:host=" . serverName . ";dbname=" . database . ";port=" . port;

    try {
        $connection = new PDO(connectionString, username, password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //    echo "success <br />";
    } catch (PDOException $exception) {
        echo "Connection Failed: {$exception->getMessage()}";
    }

?>
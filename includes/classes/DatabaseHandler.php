<?php

class DatabaseHandler
{
    public static $dbName = "laboulette";
    public static $server = "localhost";
    public static $user = "root";
    public static $password = "Julien_SqlDEV";

    private static function connect()
    {
        $conn = new PDO("mysql:host=" . self::$server . ";dbname=" . self::$dbName, self::$user, self::$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    public static function query($sql, ...$params)
    {
        if ($params != null) {
            return self::queryStmt($sql, $params);
        } else {
            return self::queryNoStmt($sql);
        }
    }

    private static function queryStmt($sql, $params)
    {
        $conn = self::connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return self::filterOutput($stmt, $sql);
    }

    private static function queryNoStmt($sql)
    {
        $conn = self::connect();
        $dataTable = $conn->query($sql);
        return self::filterOutput($dataTable, $sql);
    }

    private static function filterOutput($dataTable, $sql)
    {
        if ($dataTable->rowCount() > 0) {
            switch (explode(" ", $sql)[0]) {
                case "SELECT":
                    return $dataTable->fetchAll(PDO::FETCH_ASSOC);
                case "INSERT":
                    return $dataTable;
                case "UPDATE":
                    return $dataTable;
                default:break;
            }
        }
        return null;
    }
}
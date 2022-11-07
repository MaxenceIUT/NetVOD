<?php

namespace iutnc\deefy\db;

use PDO;

class ConnectionFactory
{

    private static string $configFile;
    private static array $config;

    private static ?PDO $pdo = null;

    public static function setConfig(string $configFile): void
    {
        self::$configFile = $configFile;
        self::$config = parse_ini_file($configFile);
    }

    public static function getConnection(): PDO
    {
        if (self::$pdo == null) {
            $dsn = self::$config["dsn"];
            $dsn = str_replace("{driver}", self::$config["driver"], $dsn);
            $dsn = str_replace("{host}", self::$config["host"], $dsn);
            $dsn = str_replace("{port}", self::$config["port"], $dsn);
            $dsn = str_replace("{database}", self::$config["database"], $dsn);
            self::$pdo = new PDO($dsn, self::$config["user"], self::$config["password"]);
        }
        return self::$pdo;
    }

}
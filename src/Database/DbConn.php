<?php

namespace PseudBot\Database;

use PDO;

class DbConn
{
    private static PDO $pdo;

    public static function get(): PDO
    {
        if (!isset(self::$pdo) || self::$pdo == null) {
            self::$pdo = new PDO(sprintf("sqlite:%s", getenv("DB_PATH")));
        }

        return self::$pdo;
    }
}

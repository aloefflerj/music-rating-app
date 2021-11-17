<?php

namespace MusicRating\Models\Helpers;

use PDO;

trait DBConnection
{
    public function conn($db, $host, $user, $passwd) 
    {
        $pdo = null;

        $dsn = "mysql:dbname={$db};host={$host};port=3306";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new \PDO($dsn, $user, $passwd, $options); 
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $pdo;
    } 
}
<?php
namespace App\Factory;

use PDO;

class DataSourceFactory
{
    public function __invoke()
    {
        return new PDO(
            "mysql:host=database;port=3306;dbname=klingogbang",
            'root',
            'example',
            [
                PDO::MYSQL_ATTR_INIT_COMMAND =>
                    "SET NAMES 'utf8', ".
                    "sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ]
        );
    }
}

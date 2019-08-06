<?php
namespace App\Factory;

use PDO;
use Psr\Container\ContainerInterface;

class DataSourceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new PDO(
            $config['pdo']['dsn'],
            $config['pdo']['user'],
            $config['pdo']['password'],
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

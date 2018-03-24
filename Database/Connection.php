<?php

namespace Database;

class Connection
{
    const DATABASE_NAME = 'leertje';
    const DATABASE_CONNECTION = '127.0.0.1';
    const DATABASE_USER = 'homestead';
    const DATABASE_PASSWORD = 'secret';
    const DATABASE_PORT = '3306';

    /**
     * @var \PDO
     */
    private static $instance;

    public static function getInstance(): \PDO
    {
        if( !static::$instance ){
            static::$instance = new \PDO(static::getDSN(), static::DATABASE_USER, static::DATABASE_PASSWORD);
        }
        return static::$instance;
    }

    private static function getDSN(): string
    {
        return "mysql:dbname=".static::DATABASE_NAME.";host=".static::DATABASE_CONNECTION.";port=".static::DATABASE_PORT;
    }

}
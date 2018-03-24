<?php
namespace Database;

trait HasConnection
{
    private function db(): \PDO
    {
        return Connection::getInstance();
    }
}
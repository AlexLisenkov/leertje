<?php
namespace Repositories;

use Database\HasConnection;
use Models\VAT;

class VatRepository
{
    use HasConnection;

    const TABLE_NAME = 'VAT';

    public function findOrCreateByPercentage( int $percentage ): VAT
    {
        if( !$this->exists($percentage) ) {
            $this->createNewRow($percentage);
        }

        return $this->firstByPercentage($percentage);
    }

    private function exists( int $percentage ): bool
    {
        $query = $this->db()->prepare('SELECT count(*) as hasRecord FROM '. static::TABLE_NAME .' WHERE Percentage = :percentage');
        $query->bindParam(':percentage', $percentage, \PDO::PARAM_INT);
        $query->execute();
        return (int) $query->fetchColumn(0) > 0;
    }

    private function firstByPercentage( int $percentage ): VAT
    {
        $query = $this->db()->prepare('SELECT * FROM '. static::TABLE_NAME .' WHERE Percentage = :percentage LIMIT 1');
        $query->bindParam(':percentage', $percentage, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject(VAT::class);
    }

    private function createNewRow( int $percentage ): bool
    {
        $insert_query = $this->db()->prepare('INSERT INTO ' . static::TABLE_NAME . ' (Name, Percentage) VALUES (:percentage, :percentage)');
        $insert_query->bindParam(':percentage', $percentage, \PDO::PARAM_INT);
        return $insert_query->execute();
    }
}
<?php
namespace Log;

class Log
{
    private function log(string $message): void
    {
        $message = '['.date('Y-m-d H:i:s').'] '.$message.PHP_EOL;
        file_put_contents(__DIR__.'/../log.txt', $message, FILE_APPEND);
    }

    public function logProductAdded(string $sku, array $row): void
    {
        $message = "Product toegevoegd: '{$sku}': '".implode(', ', $row);
        $this->log($message);
    }

    public function logProductUpdated(string $sku, array $row): void
    {
        $message = "Product gewijzigd: '{$sku}': '".implode(', ', $row);
        $this->log($message);
    }

    public function logProductRemoved(array $sku_array)
    {
        $sku_array = implode(', ', array_column($sku_array,'Sku'));
        $message = "Product(en) verwijderd: '{$sku_array}'";
        $this->log($message);
    }
}
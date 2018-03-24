<?php
namespace Repositories;

use Database\HasConnection;

class ProductRepository
{
    use HasConnection;

    const TABLE_NAME = 'Product';

    private $vatRepository;

    public function __construct(VatRepository $vatRepository)
    {
        $this->vatRepository = $vatRepository;
    }

    public function updateOrCreateBySku( string $sku, array $row): bool
    {
        if( array_key_exists('VAT', $row) ) {
            $row['VATId'] = $this->vatRepository->findOrCreateByPercentage((int)$row['VAT'])->Id;
        }

        if( !$this->skuExists($sku) ){
            $row = $this->insertRow($row);
        } else {
            $row = $this->updateRowWhereSkuEquals($sku, $row);
        }
        return $row;
    }

    private function skuExists( string $sku ): bool
    {
        $query = $this->db()->prepare('SELECT count(*) as hasRecord FROM '. static::TABLE_NAME .' WHERE Sku = :sku');
        $query->bindParam(':sku', $sku, \PDO::PARAM_STR);
        $query->execute();
        return (int) $query->fetchColumn(0) > 0;
    }

    private function insertRow( array $row ): bool
    {
        $query = $this->db()->prepare('INSERT INTO '.static::TABLE_NAME.
            '(StoreId, Name, Price, ShortDescription, FullDescription, MetaDescription, Supplier, Brand, Model, ImageId, ThumbnailId, CategoryId, VATId, AvailableSince, Sku, Percentage)
            VALUES (
                :storeId, 
                :name, 
                :price, 
                :shortDescription, 
                :fullDescription, 
                :metaDescription, 
                :supplier, 
                :brand, 
                :model, 
                :imageId, 
                :thumbnailId, 
                :categoryId, 
                :VATId, 
                :availableSince, 
                :sku, 
                :percentage
            )
        ');

        $query = $this->bindParams($query, $row);

        return $query->execute();
    }

    private function updateRowWhereSkuEquals( string $sku, array $row ): bool
    {
        $query = $this->db()->prepare("UPDATE Product SET
          StoreId = :storeId,
          Name = :name,
          Price = :price,
          ShortDescription = :shortDescription,
          FullDescription = :fullDescription,
          MetaDescription = :metaDescription,
          Supplier = :supplier,
          Brand = :brand,
          Model = :model,
          ImageId = :imageId,
          ThumbnailId = :thumbnailId,
          CategoryId = :categoryId,
          VATId = :VATId,
          AvailableSince = :availableSince,
          Percentage = :percentage,
          Sku = :sku
          WHERE
          Sku = '{$sku}'
        ");

        $query = $this->bindParams($query, $row);

        return $query->execute();
    }

    private function bindParams( \PDOStatement $query, array $row ): \PDOStatement
    {
        $storeId = $row['StoreId'] ?? null;
        $query->bindParam('storeId', $storeId, \PDO::PARAM_INT);

        $name = $row['Name'] ?? null;
        $query->bindParam('name', $name, \PDO::PARAM_STR);

        $price = $row['Price'] ?? null;
        $query->bindParam('price', $price);

        $shortDescription = $row['ShortDescription'] ?? null;
        $query->bindParam('shortDescription', $shortDescription, \PDO::PARAM_STR);

        $fullDescription = $row['FullDescription'] ?? null;
        $query->bindParam('fullDescription', $fullDescription, \PDO::PARAM_STR);

        $metaDescription = $row['metaDescription'] ?? null;
        $query->bindParam('metaDescription', $metaDescription, \PDO::PARAM_STR);

        $supplier = $row['Supplier'] ?? null;
        $query->bindParam('supplier', $supplier, \PDO::PARAM_STR);

        $brand = $row['Brand'] ?? null;
        $query->bindParam('brand', $brand, \PDO::PARAM_STR);

        $model = $row['Model'] ?? null;
        $query->bindParam('model', $model, \PDO::PARAM_STR);

        $imageId = $row['ImageId'] ?? null;
        $query->bindParam('imageId', $imageId, \PDO::PARAM_INT);

        $thumbnailId = $row['ThumbnailId'] ?? null;
        $query->bindParam('thumbnailId', $thumbnailId, \PDO::PARAM_INT);

        $categoryId = $row['CategoryId'] ?? null;
        $query->bindParam('categoryId', $categoryId, \PDO::PARAM_INT);

        $VATId = $row['VATId'] ?? null;
        $query->bindParam('VATId', $VATId, \PDO::PARAM_INT);

        $availableSince = $row['AvailableSince'] ?? null;
        $query->bindParam('availableSince', $availableSince, \PDO::PARAM_STR);

        $sku = $row['Sku'] ?? null;
        $query->bindParam('sku', $sku, \PDO::PARAM_STR);

        $percentage = $row['Percentage'] ?? null;
        $query->bindParam('percentage', $percentage, \PDO::PARAM_INT);

        return $query;
    }
}
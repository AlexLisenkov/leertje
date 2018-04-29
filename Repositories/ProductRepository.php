<?php
namespace Repositories;

use Database\HasConnection;

class ProductRepository implements IProductRepository
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
        if( array_key_exists('vat', $row) ) {
            $row['vatid'] = $this->vatRepository->findOrCreateByPercentage((int)$row['vat'])->Id;
        }

        if( !$this->skuExists($sku) ){
            $row = $this->insertRow($row);
        } else {
            $row = $this->updateRowWhereSkuEquals($sku, $row);
        }
        return $row;
    }

    public function skuExists( string $sku ): bool
    {
        $query = $this->db()->prepare('SELECT count(*) as hasRecord FROM '. static::TABLE_NAME .' WHERE Sku = :sku');
        $query->bindParam(':sku', $sku, \PDO::PARAM_STR);
        $query->execute();
        return (int) $query->fetchColumn(0) > 0;
    }

    public function removeMissingProducts(array $sku_array, string $supplier): bool
    {
        $sku_string = implode('", "', $sku_array);
        $query = $this->db()->prepare('DELETE FROM '. static::TABLE_NAME .' WHERE Sku NOT IN ("'.$sku_string.'") AND Supplier = :supplier');
        $query->bindParam(':supplier', $supplier, \PDO::PARAM_STR);
        return $query->execute();
    }

    public function findProductsThatWillBeRemoved(array $sku_array, string $supplier): array
    {
        $sku_string = implode('", "', $sku_array);
        $query = $this->db()->prepare('SELECT Sku FROM '. static::TABLE_NAME .' WHERE Sku NOT IN ("'.$sku_string.'") AND Supplier = :supplier');
        $query->bindParam(':supplier', $supplier, \PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
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
        $storeId = $row['storeid'] ?? null;
        $query->bindParam('storeId', $storeId, \PDO::PARAM_INT);

        $name = $row['name'] ?? null;
        $query->bindParam('name', $name, \PDO::PARAM_STR);

        $price = $row['price'] ?? null;
        $query->bindParam('price', $price);

        $shortDescription = $row['shortdescription'] ?? null;
        $query->bindParam('shortDescription', $shortDescription, \PDO::PARAM_STR);

        $fullDescription = $row['fulldescription'] ?? null;
        $query->bindParam('fullDescription', $fullDescription, \PDO::PARAM_STR);

        $metaDescription = $row['metadescription'] ?? null;
        $query->bindParam('metaDescription', $metaDescription, \PDO::PARAM_STR);

        $supplier = $row['supplier'] ?? null;
        $query->bindParam('supplier', $supplier, \PDO::PARAM_STR);

        $brand = $row['brand'] ?? null;
        $query->bindParam('brand', $brand, \PDO::PARAM_STR);

        $model = $row['model'] ?? null;
        $query->bindParam('model', $model, \PDO::PARAM_STR);

        $imageId = $row['imageid'] ?? null;
        $query->bindParam('imageId', $imageId, \PDO::PARAM_INT);

        $thumbnailId = $row['thumbnailid'] ?? null;
        $query->bindParam('thumbnailId', $thumbnailId, \PDO::PARAM_INT);

        $categoryId = $row['categoryid'] ?? null;
        $query->bindParam('categoryId', $categoryId, \PDO::PARAM_INT);

        $VATId = $row['vatid'] ?? null;
        $query->bindParam('VATId', $VATId, \PDO::PARAM_INT);

        $availableSince = $row['Aaailablesince'] ?? null;
        $query->bindParam('availableSince', $availableSince, \PDO::PARAM_STR);

        $sku = $row['sku'] ?? null;
        $query->bindParam('sku', $sku, \PDO::PARAM_STR);

        $percentage = $row['percentage'] ?? null;
        $query->bindParam('percentage', $percentage, \PDO::PARAM_INT);

        return $query;
    }
}
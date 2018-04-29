<?php

namespace Repositories;

use Log\Log;

class ProductRepositoryLogDecorator implements IProductRepository
{
    /**
     * @var ProductRepository
     */
    private $inner;
    /**
     * @var Log
     */
    private $logger;

    public function __construct(IProductRepository $inner)
    {
        $this->inner = $inner;
        $this->logger = new Log();
    }

    public function updateOrCreateBySku(string $sku, array $row): bool
    {
        if( $this->skuExists($sku) ){
            $this->logger->logProductUpdated($sku, $row);
        } else {
            $this->logger->logProductAdded($sku, $row);
        }

        return $this->inner->updateOrCreateBySku($sku, $row);
    }

    public function skuExists(string $sku): bool
    {
        return $this->inner->skuExists($sku);
    }

    public function removeMissingProducts(array $sku_array, string $supplier): bool
    {
        $products_sku = $this->findProductsThatWillBeRemoved($sku_array, $supplier);
        var_dump($products_sku);
        $this->logger->logProductRemoved($products_sku);
        return $this->inner->removeMissingProducts($sku_array, $supplier);
    }

    public function findProductsThatWillBeRemoved(array $sku_array, string $supplier): array
    {
        return $this->inner->findProductsThatWillBeRemoved($sku_array, $supplier);
    }
}
<?php

namespace Repositories;

interface IProductRepository
{
    public function skuExists( string $sku ): bool;
    public function updateOrCreateBySku(string $sku, array $row): bool;
    public function removeMissingProducts(array $sku_array, string $supplier): bool;
    public function findProductsThatWillBeRemoved(array $sku_array, string $supplier): array;

}
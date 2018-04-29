<?php
namespace Import;

use Repositories\IProductRepository;
use Repositories\ProductRepository;

class ImportXML implements FileImport
{
    const PROFIT_MARGIN = 1.35;

    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ReadXML
     */
    private $readXML;

    /**
     * ImportCSV constructor.
     * @param ReadXML $readXML
     * @param IProductRepository $productRepository
     */
    public function __construct( ReadXML $readXML, IProductRepository $productRepository )
    {
        $this->productRepository = $productRepository;
        $this->readXML = $readXML;
    }


    public function importFile( string $file ): void
    {
        $xml_content = $this->readXML->readFile($file);

        foreach($xml_content as $row){
            $row['price'] = $row['price'] * static::PROFIT_MARGIN;
            $row['storeid'] = 1;
            $row['supplier'] = "Schoentjes BV";
            $row['availablesince'] = date('Y-m-d H:i:s');
            $row['imageid'] = 86;
            $row['thumbnailid'] = 145;
            $row['metadescription'] = 'Zakelijke schoenen';
            $row['shortdescription'] = $row['description'];
            $row['fulldescription'] = $row['description'];
            $row['categoryid'] = 2;
            $row['vat'] = $row['vat'] * 100;
            $this->productRepository->updateOrCreateBySku($row['sku'], $row);
        }
        $this->removeNonExistentRows($xml_content);
    }

    private function removeNonExistentRows(array $rows): void
    {
        $product_sku = array_column($rows, 'sku');
        $this->productRepository->removeMissingProducts($product_sku, 'Schoentjes BV');
    }
}

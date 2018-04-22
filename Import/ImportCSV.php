<?php
namespace Import;

use Repositories\ProductRepository;

class ImportCSV implements FileImport
{
    const PROFIT_MARGIN = 1.15;

    /**
     * @var ReadCSV
     */
    private $readCSV;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ImportCSV constructor.
     * @param ReadCSV $readCSV
     * @param ProductRepository $productRepository
     */
    public function __construct( ReadCSV $readCSV, ProductRepository $productRepository )
    {
        $this->readCSV = $readCSV;
        $this->productRepository = $productRepository;
    }


    public function importFile( string $file ): void
    {
        $csv_content = $this->readCSV->readFile($file);

        foreach($csv_content as $row){
            $row['price'] = $row['price'] * static::PROFIT_MARGIN;;
            $row['storeid'] = 1;
            $row['supplier'] = "Schoeisel BV";
            $row['availablesince'] = date('Y-m-d H:i:s');
            $row['imageid'] = 34;
            $row['thumbnailid'] = 87;
            $row['metadescription'] = null;
            $row['categoryid'] = 1;
            $this->productRepository->updateOrCreateBySku($row['sku'], $row);
        }
    }
}

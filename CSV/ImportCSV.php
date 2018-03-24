<?php
namespace CSV;

use Repositories\ProductRepository;

class ImportCSV
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
            $price = $row['PrIce'] * static::PROFIT_MARGIN;
            unset($row['PrIce']);

            $row['Price'] = $price;
            $row['StoreId'] = 1;
            $row['Leverancier'] = "Schoeisel BV";
            $row['AvailableSince'] = date('Y-m-d H:i:s');
            $row['ImageId'] = 34;
            $row['ThumbnailId'] = 87;
            $row['Metadescription'] = null;
            $row['CategoryId'] = 1;
            $this->productRepository->updateOrCreateBySku($row['Sku'], $row);
        }
    }
}

<?php
namespace CSV;

class ReadCSV
{
    const DELIMITER = ";";
    const ENCLOSURE = "'";

    public function readFile( string $file ): array
    {
        $resource = fopen($file, "r");
        return $this->resourceSCVToArray($resource);
    }

    private function resourceSCVToArray( $resource ): array
    {
        $data = [];
        $header = [];

        $iterator_count = -1;

        while ( $row = fgetcsv($resource, 0, static::DELIMITER, static::ENCLOSURE) ){
            $iterator_count++;
            if( $iterator_count === 0 ){
                $header = $row;
                continue;
            }

            $data[] = array_combine($header, $row);
        }

        return $data;
    }
}
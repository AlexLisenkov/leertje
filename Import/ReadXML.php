<?php
namespace Import;

class ReadXML
{
    public function readFile( string $file ): array
    {
        $resource = fopen($file, "rb");
        $file = $this->resourceXMLToSimpleXMLToArray($resource);
        fclose($resource);
        return $file;
    }

    private function resourceXMLToSimpleXMLToArray( $resource ): array
    {
        $xmlObject = new \SimpleXMLElement(stream_get_contents($resource));
        $data = $this->simpleXMLElementToArray(((array)$xmlObject)['schoen']);
        return array_change_key_case($data, CASE_LOWER);
    }

    private function simpleXMLElementToArray($object)
    {
        if( is_iterable($object)) {
            $object = (array)$object;
            foreach ( $object as $i => $property ){
                $object[$i] = $this->simpleXMLElementToArray($property);
            }
        }
        return $object;
    }
}
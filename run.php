<?php
declare(strict_types=1);
spl_autoload_register();

$cli = new \CLI\CLIHandler();

$option = $cli->options('Import CSV', 'Import XML');

if( $option === 'Import CSV' ){
    $importer = new \Import\ImportCSV( new \Import\ReadCSV(), new \Repositories\ProductRepository( new \Repositories\VatRepository() ) );
}

if( $option === 'Import XML' ){
    $importer = new \Import\ImportXML( new \Import\ReadXML(), new \Repositories\ProductRepository( new \Repositories\VatRepository() ) );
}

$file = $cli->ask('Please specify the file');
$importer->importFile($file);

$cli->say('Looks like everything went ok.');
$cli->quit();


<?php
declare(strict_types=1);
spl_autoload_register();

$cli = new \CLI\CLIHandler();
$csvImport = new \CSV\ImportCSV( new \CSV\ReadCSV(), new \Repositories\ProductRepository( new \Repositories\VatRepository() ) );

$file = $cli->ask('Please specify the file');
$csvImport->importFile($file);

$cli->say('Looks like everything went ok.');
$cli->quit();
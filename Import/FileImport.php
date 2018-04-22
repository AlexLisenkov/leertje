<?php

namespace Import;

interface FileImport
{
    public function importFile(string $file): void;
}
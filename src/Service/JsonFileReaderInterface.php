<?php

namespace App\Service;

interface JsonFileReaderInterface
{
    public function read(string $relativePath): array;
}


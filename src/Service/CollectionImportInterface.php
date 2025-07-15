<?php

namespace App\Service;

use App\Collection\FoodCollectionInterface;

interface CollectionImportInterface
{
    /**
     * @param string $filepath
     *
     * @return array<string, FoodCollectionInterface>
     */
    public function import(string $filepath): array;
}


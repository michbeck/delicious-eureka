<?php

namespace App\Repository;

use App\Entity\FoodItemInterface;
use App\Http\Request\CollectionCriteria;
use App\Http\Request\Pagination;

interface FoodRepositoryInterface
{
    public function findByCriteria(CollectionCriteria $criteria, Pagination $pagination): array;

    public function save(FoodItemInterface $foodItem): FoodItemInterface;
}

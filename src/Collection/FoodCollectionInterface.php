<?php

namespace App\Collection;

use App\Entity\FoodItemInterface;
use App\Http\Request\CollectionCriteria;
use App\Repository\FoodRepositoryInterface;

interface FoodCollectionInterface
{
    public function add(FoodItemInterface $food): void;

    public function remove(FoodItemInterface $food): void;

    /**
     * @return FoodItemInterface[]
     */
    public function list(): array;

    public function count(): int;

    public function filter(CollectionCriteria $filter): self;

    public function getEntityClass(): string;

    public function getRepository(): FoodRepositoryInterface;
}

<?php

namespace App\Collection;

use App\Entity\Fruit;
use App\Repository\FruitRepository;

class FruitCollection extends AbstractFoodCollection
{
    public function __construct(FruitRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getEntityClass(): string
    {
        return Fruit::class;
    }
}

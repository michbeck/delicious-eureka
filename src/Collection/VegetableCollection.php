<?php

namespace App\Collection;

use App\Entity\Vegetable;
use App\Repository\VegetableRepository;

class VegetableCollection extends AbstractFoodCollection
{
    public function __construct(VegetableRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getEntityClass(): string
    {
        return Vegetable::class;
    }
}

<?php

namespace App\Service;

use App\Entity\FoodItemInterface;
use App\Exception\CollectionHandlerException;
use App\Http\Request\CreateFoodRequest;
use App\Collection\FoodCollectionInterface;
use App\Http\Request\GetCollectionRequest;

interface CollectionHandlerInterface
{
    /**
     * @return array<string, FoodCollectionInterface>
     */
    public function getCollections(): array;

    public function getCollection(string $type): FoodCollectionInterface;

    /**
     * @param list<CreateFoodRequest> $requests
     *
     * @return list<FoodItemInterface>
     *
     * @throws CollectionHandlerException
     */
    public function addItems(array $requests): array;

    public function addItem(CreateFoodRequest $request): FoodItemInterface;

    public function loadCollection(GetCollectionRequest $request): FoodCollectionInterface;
}

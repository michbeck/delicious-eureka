<?php

namespace App\Service;

use App\Collection\FoodCollectionInterface;
use App\Entity\FoodItemInterface;
use App\Exception\CollectionHandlerException;
use App\Http\Request\CreateFoodRequest;
use App\Http\Request\GetCollectionRequest;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CollectionHandler implements CollectionHandlerInterface
{
    /**
     * @param array<string, FoodCollectionInterface> $collections
     */
    public function __construct(
        protected array                 $collections = [],
        protected SerializerInterface   $serializer,
        protected DenormalizerInterface $foodItemDenormalizer,
    ) {}

    /**
     * @return array<string, FoodCollectionInterface>
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     * @param list<CreateFoodRequest> $requests
     *
     * @return list<FoodItemInterface>
     *
     * @throws CollectionHandlerException
     */
    public function addItems(array $requests): array
    {
        $foodItems = [];

        foreach ($requests as $request) {
            if (!$request instanceof CreateFoodRequest) {
                throw new CollectionHandlerException('Each request must be an instance of CreateFoodRequest.');
            }

            $foodItems[] = $this->addItem($request);
        }

        return $foodItems;
    }

    public function addItem(CreateFoodRequest $request): FoodItemInterface
    {
        $collection = $this->getCollection($request->getType());
        $data = $this->serializer->normalize($request);
        $foodItem = $this->foodItemDenormalizer->denormalize($data, $collection->getEntityClass());
        $foodItem = $collection->getRepository()->save($foodItem);

        $collection->add($foodItem);

        return $foodItem;
    }

    public function getCollection(string $type): FoodCollectionInterface
    {
        $collection = $this->collections[$type] ?? null;

        if (!$collection) {
            throw new CollectionHandlerException(sprintf('Unknown collection of type "%s".', $type));
        }

        return $collection;
    }

    public function loadCollection(GetCollectionRequest $request): FoodCollectionInterface
    {
        $collection = $this->getCollection($request->getType());
        $foodItems = $collection->getRepository()->findByCriteria(
            $request->getCriteria(),
            $request->getPagination()
        );

        foreach ($foodItems as $foodItem) {
            $collection->add($foodItem);
        }

        return $collection;
    }
}

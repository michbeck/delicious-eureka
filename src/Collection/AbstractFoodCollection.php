<?php

namespace App\Collection;

use App\Entity\FoodItemInterface;
use App\Exception\CollectionException;
use App\Http\Request\CollectionCriteria;
use App\Repository\FoodRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AbstractFoodCollection implements FoodCollectionInterface
{
    protected Collection $items;

    public function __construct(
        protected readonly FoodRepositoryInterface $repository
    )
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @param FoodItemInterface $food
     *
     * @throws CollectionException
     */
    public function add(FoodItemInterface $food): void
    {
        $this->isSupportedEntity($food);

        if (!$this->items->contains($food)) {
            $this->items->add($food);
        }
    }

    /**
     * @param FoodItemInterface $food
     *
     * @throws CollectionException if the food item is not of the supported type.
     */
    public function remove(FoodItemInterface $food): void
    {
        $this->isSupportedEntity($food);
        $this->items->removeElement($food);
    }

    /**
     * @return list<FoodItemInterface>
     */
    public function list(): array
    {
        return $this->items->toArray();
    }

    public function filter(CollectionCriteria $filter): self
    {
        $clone = clone $this;
        $clone->items = $this->items->filter(function (FoodItemInterface $item) use ($filter) {
            if ($filter->getMinQuantity() !== null && $item->getQuantity() < $filter->getMinQuantity()) {
                return false;
            }

            if ($filter->getMaxQuantity() !== null && $item->getQuantity() > $filter->getMaxQuantity()) {
                return false;
            }

            return true;
        });

        return $clone;
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function getRepository(): FoodRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param FoodItemInterface $food
     *
     * @return void
     *
     * @throws CollectionException
     */
    protected function isSupportedEntity(FoodItemInterface $food): void
    {
        $entityClass = $this->getEntityClass();

        if (!$food instanceof $entityClass) {
            throw new CollectionException(sprintf('This collection only supports %s objects.', $this->getEntityClass()));
        }
    }

    abstract public function getEntityClass(): string;
}

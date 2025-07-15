<?php

namespace App\Repository;
use App\Entity\FoodItemInterface;
use App\Http\Request\CollectionCriteria;
use App\Http\Request\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class FoodRepository extends ServiceEntityRepository implements FoodRepositoryInterface
{
    public function findByCriteria(CollectionCriteria $criteria, Pagination $pagination): array
    {
        $qb = $this->createQueryBuilder('f');

        if ($criteria->getMinQuantity() !== null) {
            $qb->andWhere('f.quantity >= :minQuantity')
                ->setParameter('minQuantity', $criteria->getMinQuantity());
        }

        if ($criteria->getMaxQuantity() !== null) {
            $qb->andWhere('f.quantity <= :maxQuantity')
                ->setParameter('maxQuantity', $criteria->getMaxQuantity());
        }

        $qb->setFirstResult($pagination->getOffset())->setMaxResults($pagination->getLimit());

        return $qb->getQuery()->getResult();
    }

    public function save(FoodItemInterface $foodItem): FoodItemInterface
    {
        $em = $this->getEntityManager();

        if (!$em->contains($foodItem)) {
            $em->persist($foodItem);

            $em->flush();
        }

        return $foodItem;
    }
}

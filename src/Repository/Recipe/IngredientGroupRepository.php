<?php

namespace App\Repository\Recipe;

use App\Entity\Recipe\IngredientGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IngredientGroup>
 *
 * @method IngredientGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientGroup[]    findAll()
 * @method IngredientGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientGroup::class);
    }

    //    /**
    //     * @return IngredientGroup[] Returns an array of IngredientGroup objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?IngredientGroup
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

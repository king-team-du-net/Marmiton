<?php

namespace App\Repository\Recipe;

use App\Entity\Recipe\RecipeHasIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeHasIngredient>
 *
 * @method RecipeHasIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeHasIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeHasIngredient[]    findAll()
 * @method RecipeHasIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeHasIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeHasIngredient::class);
    }

    //    /**
    //     * @return RecipeHasIngredient[] Returns an array of RecipeHasIngredient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RecipeHasIngredient
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

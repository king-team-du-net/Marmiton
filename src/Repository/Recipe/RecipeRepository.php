<?php

namespace App\Repository\Recipe;

use App\Entity\Recipe\Recipe;
use App\Entity\Setting\HomepageHeroSetting;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Recipe[] Returns an array of Recipe objects
     */
    public function findLastRecent(int $maxResults): array // (HomeController)
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setParameter('now', new \DateTime())
            ->where('r.createdAt <= :now')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns the recipes after applying the specified search criterias.
     *
     * @param HomepageHeroSetting|null $isOnHomepageSlider
     * @param bool                     $userEnabled
     * @param bool                     $isDraft
     * @param string                   $keyword
     * @param string                   $slug
     * @param User|null                $user
     * @param int                      $limit
     * @param string                   $sort
     * @param string                   $order
     * @param string                   $otherthan
     * @param int                      $count
     */
    public function getRecipes($isOnHomepageSlider, $addedtofavoritesby, $userEnabled, $isDraft, $keyword, $slug, $user, $limit, $sort, $order, $otherthan, $count): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r');

        if ($count) {
            $qb->select('COUNT(r)');
        } else {
            $qb->select('DISTINCT r');
        }

        if ('all' !== $keyword) {
            $qb->andWhere('r.name LIKE :keyword or :keyword LIKE r.name or :keyword LIKE r.content or r.content LIKE :keyword')->setParameter('keyword', '%'.$keyword.'%');
        }

        if ('all' !== $slug) {
            $qb->andWhere('r.slug = :slug')->setParameter('slug', $slug);
        }

        if ('all' !== $addedtofavoritesby) {
            $qb->andWhere(':addedtofavoritesbyuser MEMBER OF r.addedtofavoritesby')->setParameter('addedtofavoritesbyuser', $addedtofavoritesby);
        }

        if (true === $isOnHomepageSlider) {
            $qb->andWhere('r.isonhomepageslider IS NOT NULL');
        }

        if ('all' !== $isDraft) {
            $qb->andWhere('r.isDraft = :isDraft')->setParameter('isDraft', $isDraft);
        }

        if ('all' !== $otherthan) {
            $qb->andWhere('r.id != :otherthan')->setParameter('otherthan', $otherthan);
            $qb->andWhere('r.id = :otherthan')->setParameter('otherthan', $otherthan);
        }

        if ('all' !== $user || 'all' !== $userEnabled) {
            $qb->leftJoin('r.user', 'user');
        }

        if ('all' !== $user) {
            $qb->andWhere('user.slug = :user')->setParameter('user', $user);
        }

        if ('all' !== $userEnabled) {
            $qb->leftJoin('user', 'user');
            $qb->andWhere('user.enabled = :userEnabled')->setParameter('userEnabled', $userEnabled);
        }

        $qb->orderBy($sort, $order);

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }
}

<?php

namespace App\Repository;

use App\Entity\Testimonial;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonial>
 *
 * @method Testimonial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testimonial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testimonial[]    findAll()
 * @method Testimonial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    /**
     * @return Testimonial[] Returns an array of Testimonial objects
     */
    public function findLastRecent(int $maxResults): array // (PageController)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->where('t.isOnline = true')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns the testimonials after applying the specified search criterias.
     *
     * @param string    $keyword
     * @param string    $slug
     * @param User|null $user
     * @param Book|null $book
     * @param bool|null $isIsOnline
     * @param int|null  $rating
     * @param int       $minrating
     * @param int       $maxrating
     * @param int       $limit
     * @param int       $count
     * @param string    $sort
     * @param string    $order
     */
    public function getTestimonials($keyword, $slug, $user, $book, $isIsOnline, $rating, $minrating, $maxrating, $limit, $count, $sort, $order): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');

        if ($count) {
            $qb->select('COUNT(DISTINCT t)');
        } else {
            $qb->select('DISTINCT t');
        }

        if ('all' !== $keyword) {
            $qb->andWhere('t.rating LIKE :keyword or :keyword LIKE t.rating or t.comment LIKE :keyword or :keyword LIKE t.comment')->setParameter('keyword', '%'.$keyword.'%');
        }

        if ('all' !== $slug) {
            $qb->andWhere('t.slug = :slug')->setParameter('slug', $slug);
        }

        if ('all' !== $user) {
            $qb->leftJoin('t.user', 'user');
            $qb->andWhere('user.slug = :user')->setParameter('user', $user);
        }

        if ('all' !== $book || 'all' !== $user) {
            $qb->leftJoin('t.book', 'book');
        }

        if ('all' !== $isIsOnline) {
            $qb->andWhere('t.isIsOnline = :isIsOnline')->setParameter('isIsOnline', $isIsOnline);
        }

        if ('all' !== $rating) {
            $qb->andWhere('t.rating = :rating')->setParameter('rating', $rating);
        }

        if ('all' !== $minrating) {
            $qb->andWhere('t.rating >= :minrating')->setParameter('minrating', $minrating);
        }

        if ('all' !== $maxrating) {
            $qb->andWhere('t.rating <= :maxrating')->setParameter('maxrating', $maxrating);
        }

        if ('all' !== $limit) {
            $qb->setMaxResults($limit);
        }

        if ($sort) {
            $qb->orderBy('t.'.$sort, $order);
        }

        return $qb;
    }

    /**
     * @return Paginator<Testimonial>
     */
    public function getPaginatedTestimonials(
        int $currentPage,
        int $limit,
        ?string $keywords
    ): Paginator {
        $queryBuilder = $this->createQueryBuilder('t')
            ->addSelect('u')
            ->join('t.user', 'u')
            ->andWhere("CONCAT(u.firstname, ' ', u.lastname, ' ', t.rating) LIKE :keywords")
            ->setParameter('keywords', '%'.($keywords ?? '').'%')
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('u.firstname', 'asc')
            ->addOrderBy('u.lastname', 'asc')
            // ->where('t.isOnline = true')
        ;

        return new Paginator($queryBuilder);
    }
}

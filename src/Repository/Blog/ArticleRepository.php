<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Article;
use App\Entity\Blog\Badge as Tag;
use App\Entity\Blog\Category;
use App\Entity\Data\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorInterface $paginatorInterface,
    ) {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findLastRecent(int $maxResults): array // (HomeController)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
            ->where('a.publishedAt <= :now')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Get published articles.
     *
     * @param ?Category $category
     * @param ?Tag      $tag
     */
    public function findPublished(
        int $page,
        Category $category = null,
        Tag $tag = null,
    ): PaginationInterface {
        $data = $this->createQueryBuilder('a')
            ->where('a.publishedAt <= :now')
            ->setParameter('now', new \DateTime())
            ->addOrderBy('a.publishedAt', 'DESC')
        ;

        if (isset($category)) {
            $data = $data
                ->join('a.categories', 'c')
                ->andWhere(':category IN (c)')
                ->setParameter('category', $category)
            ;
        }

        if (isset($tag)) {
            $data = $data
                ->join('a.tags', 't')
                ->andWhere(':tag IN (t)')
                ->setParameter('tag', $tag)
            ;
        }

        $data
            ->getQuery()
            ->getResult()
        ;

        $pagination = $this->paginatorInterface->paginate($data, $page, 9);

        return $pagination;
    }

    /**
     * Get published articles thanks to Search Data value.
     */
    public function findBySearch(SearchData $searchData): PaginationInterface
    {
        $data = $this->createQueryBuilder('a')
            ->where('a.publishedAt <= :now')
            ->setParameter('now', new \DateTime())
            ->addOrderBy('a.publishedAt', 'DESC')
        ;

        if (!empty($searchData->keywords)) {
            $data = $data
                ->join('a.tags', 't')
                ->andWhere('a.title LIKE :keywords')
                ->orWhere('t.name LIKE :keywords')
                ->setParameter('keywords', "%{$searchData->keywords}%")
            ;
        }

        if (!empty($searchData->categories)) {
            $data = $data
                ->join('a.categories', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $searchData->categories)
            ;
        }

        $data = $data
            ->getQuery()
            ->getResult()
        ;

        $pagination = $this->paginatorInterface->paginate($data, $searchData->page, 9);

        return $pagination;
    }
}

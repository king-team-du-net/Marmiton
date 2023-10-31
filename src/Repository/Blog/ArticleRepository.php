<?php

namespace App\Repository\Blog;

use Doctrine\ORM\Query;
use App\Entity\Blog\Tag;
use App\Helper\Paginator;
use App\Entity\Blog\Article;
use App\Entity\Blog\Category;
use App\Entity\Data\SearchData;
use function Symfony\Component\String\u;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
     * Find articles by title & content (case insensitive).
     */
    public function searched(string $searchedTerm, int $limit = 4): array // (BlogSearchedController)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('LOWER(a.title) LIKE :searchedTerm OR LOWER(a.content) LIKE :searchedTerm')
            ->setParameter('searchedTerm', '%'.mb_strtolower($searchedTerm).'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find published articles.
     *
     * @return Query (BlogController)
     */
    public function findForPagination(Tag $tag = null): Query // ArticleAndTagsService
    {
        $qb = $this->createQueryBuilder('a')
            ->addSelect('aut', 't')
            ->innerJoin('a.author', 'aut')
            ->leftJoin('a.tags', 't')
            ->where('a.publishedAt <= :now')
            ->orderBy('a.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
        ;

        if (isset($tag)) {
            $qb
                ->leftJoin('a.tags', 't')
                ->where($qb->expr()->eq('t.id', ':id'))
                ->setParameter('id', $tag->getId())
            ;
        }

        return $qb->getQuery();
    }

    /**
     * Find articles search
     * 
     * @return Article[]
     */
    public function search(string $query, int $limit = 10/*Paginator::PAGE_SIZE*/): array // SearchedComponent
    {
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $qb = $this->createQueryBuilder('a');

        foreach ($searchTerms as $key => $term) {
            $qb
                ->orWhere('a.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }

        /** @var Article[] $result */
        $result = $qb
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }

    /**
     * Transforms the search string into an array of search terms.
     *
     * @return string[]
     */
    private function extractSearchTerms(string $searchQuery): array // SearchedComponent
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, static function ($term) {
            return 2 <= $term->length();
        });
    }








    /**
     * Get published articles
     *
     * @param int $page
     * @param ?Category $category
     * @param ?Tag $tag
     * 
     * @return PaginationInterface
     */
    public function findPublished(
        int $page,
        ?Category $category = null,
        ?Tag $tag = null,
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
     * Get published articles thanks to Search Data value
     *
     * @param SearchData $searchData
     * @return PaginationInterface
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

<?php

namespace App\Repository\Pages;

use App\Entity\Pages\Faq;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Faq>
 *
 * @method Faq|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faq|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faq[]    findAll()
 * @method Faq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faq::class);
    }

    public function save(Faq $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Faq $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFaqs(string $question, string $answer): QueryBuilder
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f');
        $qb->join('f.translations', 'translations');

        if ('all' !== $question) {
            $qb->andWhere('translations.question = :question')->setParameter('question', $question);
        }

        if ('all' !== $answer) {
            $qb->andWhere('translations.answer = :answer')->setParameter('answer', $answer);
        }

        return $qb;
    }
}

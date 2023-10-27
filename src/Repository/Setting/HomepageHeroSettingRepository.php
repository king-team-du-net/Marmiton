<?php

declare(strict_types=1);

namespace App\Repository\Setting;

use App\Entity\Setting\HomepageHeroSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomepageHeroSetting>
 *
 * @method HomepageHeroSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomepageHeroSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomepageHeroSetting[]    findAll()
 * @method HomepageHeroSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class HomepageHeroSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomepageHeroSetting::class);
    }

    public function save(HomepageHeroSetting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HomepageHeroSetting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

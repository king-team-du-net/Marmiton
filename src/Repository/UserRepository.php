<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * To get the super admin.
     */
    public function getSuperAdmin()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_SUPER_ADMIN"%')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findTeam(int $limit): array // (PageController)
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.designation', 'DESC')
            // ->andwhere('u.roles LIKE :roles')
            ->andwhere('u.suspended = :suspended')
            ->andwhere('u.team = :team')
            ->setParameter('suspended', false)
            ->setParameter('team', true)
            // ->setParameter('roles', '%"ROLE_MODERATOR"%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Query used to retrieve a user for the login.
     */
    public function findForUsersAuthenticator(string $nickname): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('LOWER(u.email) = :nickname')
            ->orWhere('LOWER(u.nickname) = :nickname')
            ->setMaxResults(1)
            ->setParameter('nickname', mb_strtolower($nickname))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

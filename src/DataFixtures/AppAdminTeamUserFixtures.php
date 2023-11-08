<?php

namespace App\DataFixtures;

use App\Entity\HasRoles;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppAdminTeamUserFixtures extends Fixture
{
    use FakerTrait;

    public function __construct(
        protected readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // User Super Admin
        /** @var User $superadmin */
        $superadmin = (new User());
        $superadmin
            ->setId(1)
            ->setRoles([HasRoles::SUPERADMIN])
            ->setLastname('Cameron')
            ->setFirstname('Williamson')
            ->setNickname('superadmin')
            ->setSlug('superadmin')
            ->setEmail('superadmin@yourdomain.com')
            ->setPhone($this->faker()->phoneNumber())
            ->setTeam(true)
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Super Admin Staff')
        ;

        $manager->persist(
            $superadmin->setPassword(
                $this->hasher->hashPassword($superadmin, 'superadmin')
            )
        );

        // User Admin
        /** @var User $admin */
        $admin = (new User());
        $admin
            ->setId(2)
            ->setRoles([HasRoles::ADMIN])
            ->setLastname('Wade')
            ->setFirstname('Warren')
            ->setNickname('admin')
            ->setSlug('admin')
            ->setEmail('admin@yourdomain.com')
            ->setPhone($this->faker()->phoneNumber())
            ->setTeam(true)
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Admin Staff')
        ;

        $manager->persist(
            $admin->setPassword(
                $this->hasher->hashPassword($admin, 'admin')
            )
        );

        // User Moderator
        /** @var User $moderator */
        $moderator = (new User());
        $moderator
            ->setId(3)
            ->setRoles([HasRoles::MODERATOR])
            ->setLastname('Jane')
            ->setFirstname('Cooper')
            ->setNickname('moderator')
            ->setSlug('moderator')
            ->setEmail('moderator@yourdomain.com')
            ->setPhone($this->faker()->phoneNumber())
            ->setTeam(true)
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Moderator Staff')
        ;

        $manager->persist(
            $moderator->setPassword(
                $this->hasher->hashPassword($moderator, 'moderator')
            )
        );

        // Create 10 Users
        $genres = ['male', 'female'];
        $genre = $this->faker()->randomElement($genres);
        for ($i = 0; $i < 10; ++$i) {
            /** @var User $user */
            $user = (new User());
            $user
                ->setLastname($this->faker()->lastName)
                ->setFirstname($this->faker()->firstName($genre))
                ->setNickname($this->faker()->unique()->userName())
                ->setEmail($this->faker()->email())
                ->setSuspended($this->faker()->boolean(0.1))
            ;

            $manager->persist(
                $user->setPassword(
                    $this->hasher->hashPassword($user, 'user')
                )
            );
        }

        $manager->flush();
    }
}

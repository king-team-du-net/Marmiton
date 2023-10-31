<?php

namespace App\DataFixtures\Blog;

use App\Entity\User;
use App\Entity\HasRoles;
use App\Entity\Blog\Badge;
use App\Entity\Blog\Article;
use App\Entity\Blog\Comment;
use App\Entity\Blog\Category;
use App\DataFixtures\FakerTrait;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\AppAdminTeamUserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppArticleFixtures extends Fixture
{
    use FakerTrait;

    public function __construct(
        protected readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create 1 User Admin
        $admins = [];

        // User Admin
        /** @var User $admin */
        $admin = (new User());
        $admin
            ->setRoles([HasRoles::ADMIN])
            ->setLastname('Tom')
            ->setFirstname('Doe')
            ->setNickname('tom-admin')
            ->setSlug('tom-admin')
            ->setEmail('tom-admin@yourdomain.com')
            ->setTeam(true)
            ->setAbout($this->faker()->realText(254))
            ->setDesignation('Admin Staff')
        ;

        $manager->persist(
            $admin->setPassword(
                $this->hasher->hashPassword($admin, 'admin')
            )
        );
        $admins[] = $admin;

        // Create 1 Customer
        $customers = [];

        /** @var User $customer */
        $customer = new User();
        $customer
            ->setLastname('John')
            ->setFirstname('Doe')            
            ->setNickname('john-customer')
            ->setSlug('john-customer')
            ->setEmail('john-customer@yourdomain.com')
        ;

        $manager->persist(
            $customer->setPassword(
                $this->hasher->hashPassword($customer, 'password')
            )
        );
        $customers[] = $customer;

        // Create 10 tags
        $badges = [];
        for ($i = 0; $i < 10; ++$i) {
            $badge = new Badge();
            $badge
                ->setName($this->faker()->unique()->word(1, true).' '.$i)
                ->setDescription(
                    1 === mt_rand(0, 1) ? $this->faker()->realText(254) : null
                )
            ;
            $manager->persist($badge);
            $badges[] = $badge;
        }

        // Create 10 Categories
        $categories = [];
        for ($i = 0; $i < 10; ++$i) {
            $category = new Category();
            $category
                ->setName($this->faker()->unique()->word(1, true).' '.$i)
                ->setDescription(
                    1 === mt_rand(0, 1) ? $this->faker()->realText(254) : null
                )
            ;
            $manager->persist($category);
            $categories[] = $category;
        }

        // Create 20 Articles
        for ($i = 0; $i < 20; ++$i) {
            $article = new Article();
            $article
                ->setTitle($this->faker()->unique()->sentence())
                ->setContent($this->faker()->realText(2000))
                ->setPublishedAt(
                    $this->faker()->boolean(75)
                    ? \DateTime::createFromInterface(
                        $this->faker()->dateTimeBetween('-50 days', '+10 days')
                    )
                    : null
                )
                ->setReadtime(rand(10, 160))
                ->setViews(rand(10, 160))
                ->setAuthor($this->faker()->boolean(50) ? $customer : $admin)
            ;

            foreach ($this->faker()->randomElements($badges, 3) as $badge) {
                $article->addBadge($badge);
            }

            foreach ($this->faker()->randomElements($categories, 3) as $category) {
                $article->addCategory($category);
            }

            $manager->persist($article);

            // Create Comments
            for ($k = 1; $k <= $this->faker()->numberBetween(1, 5); ++$k) {
                $comment = new Comment();
                $comment
                    ->setAuthor($this->faker()->boolean(50) ? $customer : $admin)
                    ->setContent($this->faker()->paragraph())
                    ->setIsApproved($this->faker()->boolean(90))
                    ->setIsRGPD(true)
                    ->setIp($this->faker()->ipv4)
                    ->setArticle($article)
                    ->setPublishedAt(new \DateTime('now + '.$k.'seconds'))
                ;

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}

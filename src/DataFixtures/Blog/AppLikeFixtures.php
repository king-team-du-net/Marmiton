<?php

namespace App\DataFixtures\Blog;

use App\DataFixtures\AppAdminTeamUserFixtures;
use App\DataFixtures\FakerTrait;
use App\Repository\Blog\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppLikeFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(
        private ArticleRepository $articleRepository,
        private UserRepository $userRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        $articles = $this->articleRepository->findAll();

        foreach ($articles as $article) {
            for ($i = 0; $i < mt_rand(0, 15); ++$i) {
                $article->addLike(
                    $users[mt_rand(0, count($users) - 1)]
                );
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppAdminTeamUserFixtures::class,
            AppArticleFixtures::class,
        ];
    }
}

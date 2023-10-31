<?php

namespace App\DataFixtures\Recipe;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\HasRoles;
use App\Entity\Testimonial;
use App\Entity\Recipe\Recipe;
use App\Entity\Recipe\Status;
use App\DataFixtures\FakerTrait;
use App\Entity\Recipe\UserRecipe;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\Recipe\TagRepository;
use App\Repository\Recipe\StepRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\AppAdminTeamUserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppRecipeFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTrait;

    public function __construct(
        protected readonly UserPasswordHasherInterface $hasher,
        protected readonly UserRepository $userRepository,
        protected readonly TagRepository $tagRepository,
        protected readonly StepRepository $stepRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create Status
        $status = [];
        foreach (['draft', 'published', 'rejected'] as $value) {
            $oneStatus = new Status();
            $oneStatus->setName($value);
            $manager->persist($oneStatus);
            $status[] = $oneStatus;
        }

        $users = $this->userRepository->findAll();

        // Create 1 User Admin
        $admins = [];

        // User Admin
        /** @var User $admin */
        $admin = (new User());
        $admin
            ->setRoles([HasRoles::ADMIN])
            ->setLastname('Anne')
            ->setFirstname('Carlier')
            ->setNickname('anne-carlier')
            ->setSlug('anne-carlier')
            ->setEmail('anne-carlier@yourdomain.com')
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

        $tags = $this->tagRepository->findAll();

        // Create 100 Recipes
        $recipes = [];
        for ($i = 0; $i < 100; ++$i) {
            $recipe = new Recipe();
            $recipe
                ->setName($this->faker()->name())
                ->setContent($this->faker()->realText(500))
                ->setDraft($this->faker()->boolean(0.1))
                ->setPreparation($this->faker()->optional(0.99)->numberBetween(5, 120))
                ->setCooking($this->faker()->optional(0.75)->numberBetween(0, 120))
                ->setBreak($this->faker()->optional(0.01)->numberBetween(0, 600))
                ->setViews(rand(10, 160))
            ;

            $recipeTags = $this->faker()->randomElements($tags, $this->faker()->randomNumber(2));
            foreach ($recipeTags as $recipeTag) {
                $recipe->addTag($recipeTag);
            }

            $manager->persist($recipe);

            $recipes[] = $recipe;
        }

        // Create 10 UserRecipe by User
        foreach ($users as $user) {
            for ($i = 0; $i < 10; ++$i) {
                $userRecipe = new UserRecipe();
                $userRecipe
                    ->setUser($this->faker()->boolean(50) ? $user : $admin)
                    ->setStatus($this->faker()->randomElement($status))
                    ->setRating($this->faker()->numberBetween(0, 5))
                    ->setComment($this->faker()->text)
                    ->setRecipe($this->faker()->randomElement($recipes))
                ;

                $manager->persist($userRecipe);
            }
        }

        // Create 10 Testimonial by User
        foreach ($users as $user) {
            for ($i = 0; $i < 10; ++$i) {
                $testimonial = new Testimonial();
                $testimonial
                    ->setUser($user)
                    ->setIsOnline($this->faker()->numberBetween(0, 1))
                    ->setRating($this->faker()->numberBetween(1, 5))
                    ->setComment($this->faker()->text)
                ;

                $manager->persist($testimonial);
            }
        }

        // Create 10 Review by Recipe
        foreach ($users as $user) {
            for ($i = 0; $i < 10; ++$i) {
                $review = new Review();
                $review
                    ->setUser($user)
                    ->setRecipe($this->faker()->randomElement($recipes))
                    ->setVisible($this->faker()->numberBetween(0, 1))
                    ->setRating($this->faker()->numberBetween(1, 5))
                    ->setHeadline($this->faker()->sentence)
                    ->setDetails($this->faker()->text)
                ;

                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppAdminTeamUserFixtures::class,
            AppTagFixtures::class,
        ];
    }
}

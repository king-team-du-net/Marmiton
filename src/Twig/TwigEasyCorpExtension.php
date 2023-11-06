<?php

namespace App\Twig;

use App\Controller\Dashboard\Admin\ArticleCrudController;
use App\Controller\Dashboard\Admin\BadgeCrudController;
use App\Controller\Dashboard\Admin\CategoryCrudController;
use App\Controller\Dashboard\Admin\PageCrudController;
use App\Entity\Blog\Article;
use App\Entity\Blog\Badge;
use App\Entity\Blog\Category;
use App\Entity\Blog\Comment;
use App\Entity\Pages\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigEasyCorpExtension extends AbstractExtension
{
    public const ADMINISTRATOR_NAMESPACE = 'App\Controller\Dashboard\Admin';

    public function __construct(
        private readonly RouterInterface $router,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly Security $security
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ea_administrator_url', [$this, 'getAdministratorUrl']),
            new TwigFunction('ea_administrator_edit_url', [$this, 'getAdministratorEditUrl']),
            new TwigFunction('entity_label', [$this, 'getEditCurrentEntityLabel']),
        ];
    }

    /**
     * @return array<TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            // new TwigFilter('menuLink', [$this, 'menuLink']),
            // new TwigFilter('categoriesToString', [$this, 'categoriesToString']),
            new TwigFilter('isCommentAuthor', [$this, 'isCommentAuthor']),
        ];
    }

    /*
    public function menuLink(Menu $menu): string
    {
        $url = $menu->getLink() ?: '#';

        if ('#' !== $url) {
            return $url;
        }

        $page = $menu->getPage();

        if ($page) {
            $name = 'pages';
            $slug = $page->getSlug();
        }

        $post = $menu->getPost();

        if ($post) {
            $name = 'blog_article';
            $slug = $post->getSlug();
        }

        $category = $menu->getCategory();

        if ($category) {
            $name = 'blog_category';
            $slug = $category->getSlug();
        }

        return $this->router->generate($name, [
            'slug' => $slug,
        ]);
    }

    public function categoriesToString(Collection $categories): string
    {
        $generateCategoryLink = function (Category $category) {
            $url = $this->router->generate('blog_category', [
                'slug' => $category->getSlug(),
            ]);

            return "<a href='$url' class='text-decoration-none' style='color: {$category->getColor()}'>{$category->getName()}</a>";
        };

        $categoryLinks = array_map($generateCategoryLink, $categories->toArray());

        return implode(', ', $categoryLinks);
    }
    */

    public function getEditCurrentEntityLabel(object $entity): string
    {
        return match ($entity::class) {
            Article::class => $this->translator->trans('Edit article'),
            Badge::class => $this->translator->trans('Edit tag'),
            Category::class => $this->translator->trans('Edit category'),
            Page::class => $this->translator->trans('Edit page')
        };
    }

    public function getAdministratorUrl(string $controller, string $action = Action::INDEX): string
    {
        return $this->adminUrlGenerator
            ->setController(self::ADMINISTRATOR_NAMESPACE.'\\'.$controller)
            ->setAction($action)
            ->generateUrl()
        ;
    }

    public function getAdministratorEditUrl(object $entity): ?string
    {
        $crudController = match ($entity::class) {
            Article::class => ArticleCrudController::class,
            Badge::class => BadgeCrudController::class,
            Category::class => CategoryCrudController::class,
            Page::class => PageCrudController::class
        };

        return $this->adminUrlGenerator
            ->setController($crudController)
            ->setAction(Action::EDIT)
            ->setEntityId($entity->getId())
            ->generateUrl()
        ;
    }

    public function isCommentAuthor(Comment $comment): bool
    {
        return $this->security->getUser() === $comment->getAuthor();
    }
}

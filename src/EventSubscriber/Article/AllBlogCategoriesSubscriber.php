<?php

namespace App\EventSubscriber\Article;

use App\Repository\Blog\CategoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class AllBlogCategoriesSubscriber implements EventSubscriberInterface
{
    public const ROUTES = ['blog', 'blog_category'];

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly Environment $twig
    ) {
    }

    public function injectGlobalVariable(RequestEvent $event): void
    {
        $route = $event->getRequest()->get('_route');

        if (in_array($route, AllBlogCategoriesSubscriber::ROUTES)) {
            $categories = $this->categoryRepository->findAll();
            $this->twig->addGlobal('allBlogCategories', $categories);
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'injectGlobalVariable'];
    }
}

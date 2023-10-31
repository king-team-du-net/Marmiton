<?php

namespace App\Controller\Blog;

use App\Form\Blog\SearchedType;
use App\Repository\Blog\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogSearchedController extends AbstractController
{
    public function __construct(private readonly PaginatorInterface $paginator)
    {
    }

    #[Route(path: '/blog/search', name: 'blog_search', methods: [Request::METHOD_GET], priority: 10)]
    public function search(Request $request): Response
    {
        return $this->render('blog/article-search.html.twig', ['query' => (string) $request->query->get('q', '')]);
    }

    #[Route(path: '/blog/searched', name: 'blog_searched', methods: [Request::METHOD_GET], priority: 10)]
    public function searched(Request $request, ArticleRepository $articleRepository): Response
    {
        $searchedForm = $this->createForm(SearchedType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $searchedQuery = $request->query->get('q');
        $searchedForm->handleRequest($request);

        if ($searchedForm->isSubmitted() && $searchedForm->isValid()) {
            $results = $articleRepository->searched($searchedQuery);
        }

        return $this->render('blog/article-searched.html.twig', [
            'searchedQuery' => $searchedQuery,
            'searchedForm' => $searchedForm,
            'results' => $results ?? [],
        ]);
    }
}

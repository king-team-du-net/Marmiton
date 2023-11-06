<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Badge as Tag;
use App\Entity\Data\SearchData;
use App\Form\Data\SearchDataType;
use App\Repository\Blog\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/blog')]
class BlogTagController extends AbstractController
{
    #[Route(path: '/tags/{slug}', name: 'blog_tag', defaults: ['_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Route(path: '/rss.xml', name: 'blog_rss', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function list(Request $request, string $_format, ArticleRepository $articleRepository, Tag $tag): Response
    {
        $searchData = new SearchData();

        $form = $this->createForm(SearchDataType::class, $searchData)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $pagination = $articleRepository->findBySearch($searchData);

            return $this->render('blog/list.'.$_format.'.twig', compact('form', 'pagination', 'tag'));
        }

        $pagination = $articleRepository->findPublished($request->query->getInt('page', 1), null, $tag);

        return $this->render('blog/tag.html.twig', compact('form', 'pagination', 'tag'));
    }
}

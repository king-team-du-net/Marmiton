<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Article;
use App\Entity\Data\SearchData;
use App\Form\Data\SearchDataType;
use App\Repository\Blog\TagRepository;
use App\Service\ArticleAndTagsService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Blog\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/blog')]
class BlogController extends AbstractController
{
    #[Route(path: '/', name: 'blog', defaults: ['_format' => 'html'], methods: [Request::METHOD_GET])]
    #[Route(path: '/rss.xml', name: 'blog_rss', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function list(Request $request, string $_format, ArticleRepository $articleRepository): Response
    {
        $searchData = new SearchData();

        $form = $this->createForm(SearchDataType::class, $searchData)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $pagination = $articleRepository->findBySearch($searchData);

            return $this->render('blog/article-list.'.$_format.'.twig', compact('form', 'pagination'));
        }

        $pagination = $articleRepository->findPublished($request->query->getInt('page', 1));

        return $this->render('blog/article-list.'.$_format.'.twig', compact('form', 'pagination'));
    }




    //#[Route(path: '/', name: 'blog', defaults: ['_format' => 'html'], methods: [Request::METHOD_GET])]
    //#[Route(path: '/rss.xml', name: 'blog_rss', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    //#[Route(path: '/tag/{slug}', name: 'blog_tag', defaults: ['_format' => 'html'], methods: [Request::METHOD_GET])]
    //#[Cache(smaxage: 10)]
    public function list2(Request $request, string $_format, ?string $slug, ArticleAndTagsService $articleAndTagsService, TagRepository $tagRepository): Response
    {
        $tag = null;
        if ($slug) {
            $tag = $tagRepository->findOneBySlug($slug);
        }
        
        /*
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tagRepository->findOneBy(['slug' => $request->query->get('tag')]);
        }
        */

        $pagination = $articleAndTagsService->getPaginatedRecentPublishedArticlesAndTags();

        return $this->render('blog/article-list.'.$_format.'.twig', [
            'pagination' => $pagination,
            'tagName' => $tag?->getName(),
        ]);
    }

    #[Route(path: '/{slug}', name: 'blog_show', methods: [Request::METHOD_GET])]
    public function show(Article $article, EntityManagerInterface $em): Response
    {
        if (!$article) {
            return $this->redirectToRoute('blog');
        }

        // Number of views
        $article->viewed();
        $em->persist($article);
        $em->flush();

        return $this->render('blog/article-detail.html.twig', [
            'entity' => $article,
        ]);
    }
}

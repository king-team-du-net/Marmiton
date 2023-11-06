<?php

namespace App\Controller;

use App\Repository\Blog\ArticleRepository;
use App\Repository\Pages\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'sitemap', methods: [Request::METHOD_GET])]
    public function sitemap(
        Request $request,
        ArticleRepository $articleRepository,
        PageRepository $pageRepository
    ): Response {
        $hostname = $request->getSchemeAndHttpHost();

        // Register static pages urls
        $urls = [];

        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('blog')];
        $urls[] = ['loc' => $this->generateUrl('contact')];
        $urls[] = ['loc' => $this->generateUrl('team')];
        $urls[] = ['loc' => $this->generateUrl('faq')];
        $urls[] = ['loc' => $this->generateUrl('testimonial')];
        $urls[] = ['loc' => $this->generateUrl('security_login')];
        // $urls[] = ['loc' => $this->generateUrl('reset_password')];

        // dd($urls);

        // Register pages urls
        foreach ($pageRepository->findAll() as $page) {
            $urls[] = [
                'loc' => $this->generateUrl('page', ['slug' => $page->getSlug()]),
                'lastmod' => $page->getCreatedAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        // Register articles urls
        $articles = $articleRepository->findBy([], ['publishedAt' => 'DESC']);
        foreach ($articles as $article) {
            $urls[] = [
                'loc' => $this->generateUrl('blog_show', ['slug' => $article->getSlug()]),
                'lastmod' => $article->getUpdatedAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        $response = $this->render('pages/sitemap.html.twig', compact('urls', 'hostname'));

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}

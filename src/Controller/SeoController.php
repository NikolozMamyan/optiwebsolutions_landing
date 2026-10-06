<?php

namespace App\Controller;

use App\Service\EditorialContent;
use App\Service\SearchIndex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

#[Cache(public: true, maxage: 300)]
final class SeoController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', methods: ['GET'])]
    public function sitemap(SearchIndex $index): Response
    {
        return $this->render('seo/sitemap.xml.twig', ['pages' => $index->pages()], new Response('', 200, ['Content-Type' => 'application/xml; charset=UTF-8']));
    }

    #[Route('/robots.txt', name: 'app_robots', methods: ['GET'])]
    public function robots(): Response
    {
        return $this->render('seo/robots.txt.twig', [], new Response('', 200, ['Content-Type' => 'text/plain; charset=UTF-8']));
    }

    #[Route('/llms.txt', name: 'app_llms', methods: ['GET'])]
    public function llms(EditorialContent $editorial): Response
    {
        return $this->render('seo/llms.txt.twig', ['articles' => $editorial->articles(), 'cases' => $editorial->cases()], new Response('', 200, ['Content-Type' => 'text/plain; charset=UTF-8']));
    }

    #[Route(path: ['fr' => '/blog/feed.xml', 'en' => '/en/blog/feed.xml'], name: 'app_blog_feed', methods: ['GET'])]
    public function feed(EditorialContent $editorial): Response
    {
        return $this->render('seo/feed.xml.twig', ['articles' => $editorial->articles()], new Response('', 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']));
    }

    #[Route(path: ['fr' => '/blog/{slug}.md', 'en' => '/en/blog/{slug}.md'], name: 'app_blog_markdown', requirements: ['slug' => '[a-z0-9-]+'], methods: ['GET'])]
    public function articleMarkdown(string $slug, Request $request, EditorialContent $editorial): Response
    {
        $entry = $editorial->findArticle($slug, $request->getLocale()) ?? throw $this->createNotFoundException();

        return $this->render('seo/editorial.md.twig', ['entry' => $entry, 'kind' => 'article'], new Response('', 200, ['Content-Type' => 'text/markdown; charset=UTF-8', 'X-Robots-Tag' => 'noindex, follow']));
    }

    #[Route(path: ['fr' => '/etudes-de-cas/{slug}.md', 'en' => '/en/case-studies/{slug}.md'], name: 'app_case_markdown', requirements: ['slug' => '[a-z0-9-]+'], methods: ['GET'])]
    public function caseMarkdown(string $slug, Request $request, EditorialContent $editorial): Response
    {
        $entry = $editorial->findCase($slug, $request->getLocale()) ?? throw $this->createNotFoundException();

        return $this->render('seo/editorial.md.twig', ['entry' => $entry, 'kind' => 'case'], new Response('', 200, ['Content-Type' => 'text/markdown; charset=UTF-8', 'X-Robots-Tag' => 'noindex, follow']));
    }
}

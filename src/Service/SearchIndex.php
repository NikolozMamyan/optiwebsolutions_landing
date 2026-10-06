<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SearchIndex
{
    public function __construct(
        private readonly EditorialContent $editorial,
        private readonly UrlGeneratorInterface $router,
        #[Autowire('%env(DEFAULT_URI)%')] private readonly string $siteUrl,
    ) {
    }

    public function pages(): array
    {
        $pages = [];
        foreach (['app_home', 'app_contact', 'app_blog', 'app_case_studies', 'app_legal', 'app_privacy_policy', 'app_terms_and_conditions'] as $route) {
            $pages[] = ['urls' => $this->urls($route)];
        }
        foreach (['app_blog_article' => $this->editorial->articles(), 'app_case_study' => $this->editorial->cases()] as $route => $entries) {
            foreach ($entries as $entry) {
                $pages[] = ['urls' => $this->urls($route, $entry['slugs']), 'modified' => $entry['modified'], 'cover' => $entry['cover']];
            }
        }

        return $pages;
    }

    private function urls(string $route, array $slugs = []): array
    {
        $urls = [];
        foreach (['fr', 'en'] as $locale) {
            $parameters = ['_locale' => $locale];
            if ($slugs) {
                $parameters['slug'] = $slugs[$locale];
            }
            $urls[$locale] = rtrim($this->siteUrl, '/').$this->router->generate($route, $parameters);
        }

        return $urls;
    }
}

<?php

namespace App\Service;

final class ServiceContent
{
    private const SERVICES = [
        'website' => [
            'key' => 'service.website',
            'slugs' => ['fr' => 'creation-site-web-strasbourg', 'en' => 'website-development-strasbourg'],
            'cover' => 'projects/lnstrade.webp', 'width' => 1440, 'height' => 960,
            'article_ids' => ['website', 'budget', 'wordpress_symfony'], 'case_ids' => ['lnstrade', 'ultrapop', 'catalogue'],
        ],
        'symfony' => [
            'key' => 'service.symfony',
            'slugs' => ['fr' => 'developpement-symfony-sur-mesure', 'en' => 'custom-symfony-development'],
            'cover' => 'projects/les-consultants-dashboard.webp', 'width' => 1889, 'height' => 887,
            'article_ids' => ['application', 'excel', 'learning'], 'case_ids' => ['consultants', 'mykeynest'],
        ],
        'saas' => [
            'key' => 'service.saas',
            'slugs' => ['fr' => 'creation-application-web-saas', 'en' => 'web-application-saas-development'],
            'cover' => 'projects/mykeynest-blurred.webp', 'width' => 1819, 'height' => 865,
            'article_ids' => ['application', 'b2b', 'agents'], 'case_ids' => ['mykeynest', 'consultants'],
        ],
        'managed' => [
            'key' => 'service.managed',
            'slugs' => ['fr' => 'infogerance-maintenance-informatique', 'en' => 'managed-it-website-maintenance'],
            'cover' => 'blog/infogerance-tpe-pme-strasbourg.webp', 'width' => 1536, 'height' => 1024,
            'article_ids' => ['managed', 'redesign', 'application'], 'case_ids' => [],
        ],
    ];

    public function all(): array
    {
        return self::SERVICES;
    }

    public function find(string $slug, string $locale): ?array
    {
        foreach (self::SERVICES as $service) {
            if ($service['slugs'][$locale] === $slug) {
                return $service;
            }
        }

        return null;
    }
}

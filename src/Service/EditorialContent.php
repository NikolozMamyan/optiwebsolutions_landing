<?php

namespace App\Service;

final class EditorialContent
{
    private const ARTICLES = [
        [
            'id' => 'ai_visibility', 'key' => 'blog.ai_visibility',
            'slugs' => ['fr' => 'referencement-site-moteurs-ia-chatgpt-google', 'en' => 'website-visibility-ai-search'],
            'cover' => 'blog/referencement-moteurs-ia.webp', 'cover_small' => 'blog/referencement-moteurs-ia-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'eligibility' => ['paragraphs' => 2, 'bullets' => 3],
                'bots' => ['paragraphs' => 2, 'bullets' => 0],
                'pages' => ['paragraphs' => 2, 'bullets' => 4],
                'proof' => ['paragraphs' => 2, 'bullets' => 0],
                'measure' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => [],
            'related_article_ids' => ['seo', 'ai_seo'],
            'sources' => [
                ['name' => 'Google Search Central — Visibilité dans les fonctionnalités IA', 'url' => 'https://developers.google.com/search/docs/fundamentals/ai-optimization-guide'],
                ['name' => 'OpenAI — Robots de recherche et d’entraînement', 'url' => 'https://developers.openai.com/api/docs/bots'],
            ],
        ],
        [
            'id' => 'ai_seo', 'key' => 'blog.ai_seo',
            'slugs' => ['fr' => 'seo-intelligence-artificielle-methode', 'en' => 'seo-with-ai-practical-workflow'],
            'cover' => 'blog/seo-avec-intelligence-artificielle.webp', 'cover_small' => 'blog/seo-avec-intelligence-artificielle-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'intent' => ['paragraphs' => 2, 'bullets' => 3],
                'brief' => ['paragraphs' => 2, 'bullets' => 4],
                'draft' => ['paragraphs' => 2, 'bullets' => 0],
                'review' => ['paragraphs' => 2, 'bullets' => 4],
                'iterate' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => [],
            'related_article_ids' => ['seo', 'ai_visibility'],
            'sources' => [
                ['name' => 'Google Search Central — Contenus créés avec l’IA', 'url' => 'https://developers.google.com/search/docs/fundamentals/using-gen-ai-content'],
            ],
        ],
        [
            'id' => 'agents', 'key' => 'blog.agents',
            'slugs' => ['fr' => 'agents-ia-entreprise-fonctionnement', 'en' => 'business-ai-agents-how-they-work'],
            'cover' => 'blog/agents-ia-entreprise.webp', 'cover_small' => 'blog/agents-ia-entreprise-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'mechanism' => ['paragraphs' => 2, 'bullets' => 4],
                'choice' => ['paragraphs' => 2, 'bullets' => 0],
                'pilot' => ['paragraphs' => 2, 'bullets' => 4],
                'acquire' => ['paragraphs' => 2, 'bullets' => 4],
                'control' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => [],
            'related_article_ids' => ['application', 'ai_seo'],
            'sources' => [
                ['name' => 'OpenAI — Définir un agent IA', 'url' => 'https://developers.openai.com/api/docs/guides/agents/define-agents'],
                ['name' => 'OpenAI — Contrôles et validations humaines', 'url' => 'https://developers.openai.com/api/docs/guides/agents/guardrails-approvals'],
                ['name' => 'Anthropic — Concevoir des agents efficaces', 'url' => 'https://www.anthropic.com/engineering/building-effective-agents'],
            ],
        ],
        [
            'id' => 'budget', 'key' => 'blog.budget',
            'slugs' => ['fr' => 'prix-site-vitrine-strasbourg', 'en' => 'business-website-cost-strasbourg'],
            'cover' => 'blog/prix-site-vitrine-strasbourg.webp', 'cover_small' => 'blog/prix-site-vitrine-strasbourg-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'reference' => ['paragraphs' => 2, 'bullets' => 3],
                'scope' => ['paragraphs' => 2, 'bullets' => 4],
                'recurring' => ['paragraphs' => 2, 'bullets' => 0],
                'compare' => ['paragraphs' => 2, 'bullets' => 4],
                'brief' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => ['lnstrade'],
            'related_article_ids' => ['website', 'artisan'],
            'sources' => [
                ['name' => 'France Num — Cahier des charges et budget d’un site', 'url' => 'https://www.francenum.gouv.fr/guides-et-conseils/developpement-commercial/site-web/batir-le-cahier-des-charges-du-site-internet'],
            ],
        ],
        [
            'id' => 'redesign', 'key' => 'blog.redesign',
            'slugs' => ['fr' => 'refonte-site-internet-preserver-seo', 'en' => 'website-redesign-preserve-seo'],
            'cover' => 'blog/refonte-site-internet-seo.webp', 'cover_small' => 'blog/refonte-site-internet-seo-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'audit' => ['paragraphs' => 2, 'bullets' => 4],
                'mapping' => ['paragraphs' => 2, 'bullets' => 0],
                'content' => ['paragraphs' => 2, 'bullets' => 0],
                'languages' => ['paragraphs' => 2, 'bullets' => 4],
                'monitor' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => ['lnstrade'],
            'related_article_ids' => ['seo', 'budget'],
            'sources' => [
                ['name' => 'Google Search Central — Migrations de sites', 'url' => 'https://developers.google.com/search/docs/crawling-indexing/site-move-with-url-changes?hl=fr'],
                ['name' => 'Google Search Central — Versions linguistiques', 'url' => 'https://developers.google.com/search/docs/specialty/international/localized-versions?hl=fr'],
            ],
        ],
        [
            'id' => 'artisan', 'key' => 'blog.artisan',
            'slugs' => ['fr' => 'site-internet-artisan-strasbourg-alsace', 'en' => 'trade-business-website-strasbourg-alsace'],
            'cover' => 'blog/site-internet-artisan-strasbourg.webp', 'cover_small' => 'blog/site-internet-artisan-strasbourg-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'services' => ['paragraphs' => 2, 'bullets' => 3],
                'projects' => ['paragraphs' => 2, 'bullets' => 0],
                'local' => ['paragraphs' => 2, 'bullets' => 4],
                'mobile' => ['paragraphs' => 2, 'bullets' => 0],
                'launch' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => [],
            'related_article_ids' => ['budget', 'website'],
            'sources' => [
                ['name' => 'Google Business Profile — Classement local', 'url' => 'https://support.google.com/business/answer/7091?hl=fr'],
                ['name' => 'France Num — Présence en ligne des artisans', 'url' => 'https://www.francenum.gouv.fr/guides-et-conseils/strategie-numerique/plan-daction/artisans-dart-comment-mettre-en-place-une'],
            ],
        ],
        [
            'id' => 'b2b', 'key' => 'blog.b2b',
            'slugs' => ['fr' => 'catalogue-b2b-en-ligne-commandes-devis', 'en' => 'online-b2b-catalogue-orders-quotes'],
            'cover' => 'blog/catalogue-b2b-commandes-devis.webp', 'cover_small' => 'blog/catalogue-b2b-commandes-devis-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'journey' => ['paragraphs' => 2, 'bullets' => 4],
                'data' => ['paragraphs' => 2, 'bullets' => 0],
                'accounts' => ['paragraphs' => 2, 'bullets' => 4],
                'markets' => ['paragraphs' => 2, 'bullets' => 0],
                'delivery' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => ['catalogue', 'ultrapop'],
            'related_article_ids' => ['application', 'redesign'],
            'sources' => [
                ['name' => 'Shopify — Parcours de commande B2B', 'url' => 'https://help.shopify.com/fr/manual/b2b/checkout-and-orders/draft-orders'],
                ['name' => 'Symfony — Contrôle des accès', 'url' => 'https://symfony.com/doc/7.4/security.html'],
            ],
        ],
        [
            'id' => 'excel', 'key' => 'blog.excel',
            'slugs' => ['fr' => 'remplacer-excel-application-metier-sur-mesure', 'en' => 'replace-spreadsheets-custom-business-application'],
            'cover' => 'blog/remplacer-excel-application-metier.webp', 'cover_small' => 'blog/remplacer-excel-application-metier-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'signals' => ['paragraphs' => 2, 'bullets' => 4],
                'process' => ['paragraphs' => 2, 'bullets' => 0],
                'choice' => ['paragraphs' => 2, 'bullets' => 4],
                'migration' => ['paragraphs' => 2, 'bullets' => 0],
                'project' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => ['mykeynest'],
            'related_article_ids' => ['application', 'b2b'],
            'sources' => [
                ['name' => 'Microsoft — Collaboration dans Excel', 'url' => 'https://learn.microsoft.com/fr-fr/office/vba/excel/concepts/about-coauthoring-in-excel'],
                ['name' => 'Symfony — Workflow', 'url' => 'https://symfony.com/doc/current/workflow.html'],
            ],
        ],
        [
            'id' => 'learning', 'key' => 'blog.learning',
            'slugs' => ['fr' => 'creation-plateforme-formation-en-ligne', 'en' => 'build-online-training-platform'],
            'cover' => 'blog/creation-plateforme-elearning.webp', 'cover_small' => 'blog/creation-plateforme-elearning-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 5,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'journey' => ['paragraphs' => 2, 'bullets' => 4],
                'management' => ['paragraphs' => 2, 'bullets' => 0],
                'choice' => ['paragraphs' => 2, 'bullets' => 4],
                'international' => ['paragraphs' => 2, 'bullets' => 0],
                'budget' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => ['consultants'],
            'related_article_ids' => ['application', 'excel'],
            'sources' => [
                ['name' => 'MoodleDocs — Achèvement d’un cours', 'url' => 'https://docs.moodle.org/en/Course_completion'],
                ['name' => 'Symfony — Sécurité et rôles', 'url' => 'https://symfony.com/doc/7.4/security.html'],
            ],
        ],
        [
            'id' => 'managed', 'key' => 'blog.managed',
            'slugs' => ['fr' => 'infogerance-tpe-pme-strasbourg-alsace', 'en' => 'managed-it-small-business-strasbourg-alsace'],
            'cover' => 'blog/infogerance-tpe-pme-strasbourg.webp', 'cover_small' => 'blog/infogerance-tpe-pme-strasbourg-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 4,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'inventory' => ['paragraphs' => 2, 'bullets' => 4],
                'scope' => ['paragraphs' => 2, 'bullets' => 0],
                'backups' => ['paragraphs' => 2, 'bullets' => 4],
                'budget' => ['paragraphs' => 2, 'bullets' => 0],
                'start' => ['paragraphs' => 2, 'bullets' => 0],
            ],
            'faq_count' => 3, 'case_ids' => [],
            'related_article_ids' => ['excel', 'redesign'],
            'sources' => [
                ['name' => 'Cybermalveillance.gouv.fr — Sauvegardes et restauration', 'url' => 'https://www.cybermalveillance.gouv.fr/tous-nos-contenus/bonnes-pratiques/sauvegardes'],
            ],
        ],
        [
            'id' => 'website', 'key' => 'blog.website',
            'slugs' => ['fr' => 'creation-site-web-strasbourg', 'en' => 'website-development-strasbourg'],
            'cover' => 'blog/creation-site-web-strasbourg.webp',
            'cover_small' => 'blog/creation-site-web-strasbourg-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 6,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'objective' => ['paragraphs' => 2, 'bullets' => 4],
                'structure' => ['paragraphs' => 2, 'bullets' => 0],
                'local' => ['paragraphs' => 2, 'bullets' => 0],
                'budget' => ['paragraphs' => 2, 'bullets' => 4],
                'launch' => ['paragraphs' => 2, 'bullets' => 4],
            ],
            'faq_count' => 2, 'case_ids' => ['lnstrade', 'ultrapop'],
            'sources' => [
                ['name' => 'Google Search Central', 'url' => 'https://developers.google.com/search/docs/fundamentals/seo-starter-guide?hl=fr'],
                ['name' => 'Google Business Profile', 'url' => 'https://support.google.com/business/answer/7091?hl=fr'],
            ],
        ],
        [
            'id' => 'application', 'key' => 'blog.application',
            'slugs' => ['fr' => 'application-metier-sur-mesure-symfony', 'en' => 'custom-business-application-symfony'],
            'cover' => 'blog/application-metier-symfony.webp',
            'cover_small' => 'blog/application-metier-symfony-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 6,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'need' => ['paragraphs' => 2, 'bullets' => 3],
                'framework' => ['paragraphs' => 2, 'bullets' => 0],
                'scope' => ['paragraphs' => 2, 'bullets' => 4],
                'integration' => ['paragraphs' => 2, 'bullets' => 0],
                'maintenance' => ['paragraphs' => 2, 'bullets' => 4],
            ],
            'faq_count' => 2, 'case_ids' => ['consultants', 'mykeynest'],
            'sources' => [
                ['name' => 'Symfony Documentation', 'url' => 'https://symfony.com/doc/7.4/index.html'],
                ['name' => 'Symfony Security', 'url' => 'https://symfony.com/doc/7.4/security.html'],
            ],
        ],
        [
            'id' => 'seo', 'key' => 'blog.seo',
            'slugs' => ['fr' => 'referencement-naturel-site-web', 'en' => 'website-seo-practical-guide'],
            'cover' => 'blog/referencement-naturel-site-web.webp',
            'cover_small' => 'blog/referencement-naturel-site-web-640.webp',
            'width' => 1536, 'height' => 1024, 'minutes' => 7,
            'published' => '2026-10-06', 'modified' => '2026-10-06',
            'sections' => [
                'intent' => ['paragraphs' => 2, 'bullets' => 3],
                'indexing' => ['paragraphs' => 2, 'bullets' => 4],
                'content' => ['paragraphs' => 2, 'bullets' => 0],
                'performance' => ['paragraphs' => 2, 'bullets' => 0],
                'measure' => ['paragraphs' => 2, 'bullets' => 4],
            ],
            'faq_count' => 2, 'case_ids' => ['lnstrade', 'catalogue'],
            'sources' => [
                ['name' => 'Google Search Central — SEO', 'url' => 'https://developers.google.com/search/docs/fundamentals/seo-starter-guide?hl=fr'],
                ['name' => 'Google Search Central — Sitemaps', 'url' => 'https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap?hl=fr'],
                ['name' => 'web.dev — Core Web Vitals', 'url' => 'https://web.dev/articles/vitals'],
            ],
        ],
    ];

    private const CASES = [
        [
            'id' => 'consultants', 'key' => 'case.consultants', 'client' => 'Les-Consultants',
            'slugs' => ['fr' => 'les-consultants-plateforme-elearning', 'en' => 'les-consultants-elearning-platform'],
            'cover' => 'projects/les-consultants-dashboard.webp', 'cover_small' => 'projects/les-consultants-dashboard-640.webp', 'width' => 1889, 'height' => 887,
            'url' => 'https://elearning-lesconsultants.com', 'tint' => '#d7efe7',
            'published' => '2026-10-06', 'modified' => '2026-10-06', 'minutes' => 3,
            'sections' => ['brief' => ['paragraphs' => 2, 'bullets' => 0], 'design' => ['paragraphs' => 2, 'bullets' => 3], 'journey' => ['paragraphs' => 2, 'bullets' => 0]],
            'article_id' => 'application',
        ],
        [
            'id' => 'lnstrade', 'key' => 'case.lnstrade', 'client' => 'Lnstrade',
            'slugs' => ['fr' => 'lnstrade-site-corporate', 'en' => 'lnstrade-corporate-website'],
            'cover' => 'projects/lnstrade.webp', 'cover_small' => 'projects/lnstrade-640.webp', 'width' => 1440, 'height' => 960,
            'url' => 'https://lnstrade.fr/en/', 'tint' => '#fff0be',
            'published' => '2026-10-06', 'modified' => '2026-10-06', 'minutes' => 3,
            'sections' => ['brief' => ['paragraphs' => 2, 'bullets' => 0], 'design' => ['paragraphs' => 2, 'bullets' => 3], 'journey' => ['paragraphs' => 2, 'bullets' => 0]],
            'article_id' => 'website',
        ],
        [
            'id' => 'mykeynest', 'key' => 'case.mykeynest', 'client' => 'MYKEYNEST',
            'slugs' => ['fr' => 'mykeynest-gestion-acces-entreprise', 'en' => 'mykeynest-business-access-management'],
            'cover' => 'projects/mykeynest-blurred.webp', 'cover_small' => 'projects/mykeynest-blurred-640.webp', 'width' => 1819, 'height' => 865,
            'url' => 'https://key-nest.com', 'tint' => '#d7efe7',
            'published' => '2026-10-06', 'modified' => '2026-10-06', 'minutes' => 3,
            'sections' => ['brief' => ['paragraphs' => 2, 'bullets' => 0], 'design' => ['paragraphs' => 2, 'bullets' => 3], 'journey' => ['paragraphs' => 2, 'bullets' => 0]],
            'article_id' => 'application',
        ],
        [
            'id' => 'ultrapop', 'key' => 'case.ultrapop', 'client' => 'ULTRAPOP',
            'slugs' => ['fr' => 'ultrapop-boutique-en-ligne', 'en' => 'ultrapop-online-store'],
            'cover' => 'projects/ultrapop.webp', 'cover_small' => 'projects/ultrapop-640.webp', 'width' => 1440, 'height' => 960,
            'url' => 'https://ultrapop.com', 'tint' => '#ffdfd0',
            'published' => '2026-10-06', 'modified' => '2026-10-06', 'minutes' => 3,
            'sections' => ['brief' => ['paragraphs' => 2, 'bullets' => 0], 'design' => ['paragraphs' => 2, 'bullets' => 3], 'journey' => ['paragraphs' => 2, 'bullets' => 0]],
            'article_id' => 'website',
        ],
        [
            'id' => 'catalogue', 'key' => 'case.catalogue', 'client' => 'Catalogue digital',
            'slugs' => ['fr' => 'catalogue-digital-interactif', 'en' => 'interactive-digital-catalogue'],
            'cover' => 'projects/catalogue.webp', 'cover_small' => 'projects/catalogue-640.webp', 'width' => 1440, 'height' => 960,
            'url' => 'https://lnstrade.fr/catalogue', 'tint' => '#dae8fb',
            'published' => '2026-10-06', 'modified' => '2026-10-06', 'minutes' => 3,
            'sections' => ['brief' => ['paragraphs' => 2, 'bullets' => 0], 'design' => ['paragraphs' => 2, 'bullets' => 3], 'journey' => ['paragraphs' => 2, 'bullets' => 0]],
            'article_id' => 'seo',
        ],
    ];

    public function articles(): array
    {
        return self::ARTICLES;
    }

    public function cases(): array
    {
        return self::CASES;
    }

    public function findArticle(string $slug, string $locale): ?array
    {
        return $this->find(self::ARTICLES, $slug, $locale);
    }

    public function findCase(string $slug, string $locale): ?array
    {
        return $this->find(self::CASES, $slug, $locale);
    }

    private function find(array $entries, string $slug, string $locale): ?array
    {
        foreach ($entries as $entry) {
            if ($entry['slugs'][$locale] === $slug) {
                return $entry;
            }
        }

        return null;
    }
}

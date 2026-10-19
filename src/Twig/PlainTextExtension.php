<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PlainTextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('plain_text', static fn (string $value): string => html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'))];
    }
}

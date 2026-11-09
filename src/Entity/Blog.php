<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80, unique: true)]
    private string $reference;

    #[ORM\Column(type: 'json')]
    private array $metadata;

    #[ORM\Column(type: 'json')]
    private array $translations;

    public function __construct(array $metadata, array $translations)
    {
        $this->reference = $metadata['id'];
        $this->metadata = $metadata;
        $this->translations = $translations;
    }

    public function getId(): ?int { return $this->id; }
    public function getReference(): string { return $this->reference; }
    public function getMetadata(): array { return $this->metadata; }
    public function getTranslations(): array { return $this->translations; }
}

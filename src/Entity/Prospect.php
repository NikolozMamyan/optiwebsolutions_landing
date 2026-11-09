<?php

namespace App\Entity;

use App\Repository\ProspectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProspectRepository::class)]
#[ORM\Index(columns: ['created_at'])]
class Prospect
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100), Assert\NotBlank, Assert\Length(max: 100)]
    private string $firstName;

    #[ORM\Column(length: 100), Assert\NotBlank, Assert\Length(max: 100)]
    private string $lastName;

    #[ORM\Column(length: 180), Assert\NotBlank, Assert\Email, Assert\Length(max: 180)]
    private string $email;

    #[ORM\Column(length: 40, nullable: true), Assert\Length(max: 40)]
    private ?string $phone;

    #[ORM\Column(type: 'text', nullable: true), Assert\Length(max: 5000)]
    private ?string $message;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $firstName, string $lastName, string $email, string $phone = '', string $message = '')
    {
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
        $this->email = mb_strtolower(trim($email));
        $this->phone = trim($phone) ?: null;
        $this->message = trim($message) ?: null;
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    public function getId(): ?int { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getMessage(): ?string { return $this->message; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}

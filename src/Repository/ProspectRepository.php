<?php

namespace App\Repository;

use App\Entity\Prospect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProspectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prospect::class);
    }

    public function page(int $page, int $limit = 20): array
    {
        return $this->findBy([], ['createdAt' => 'DESC', 'id' => 'DESC'], $limit, ($page - 1) * $limit);
    }

    public function countSince(\DateTimeImmutable $date): int
    {
        return (int) $this->createQueryBuilder('p')->select('COUNT(p.id)')
            ->where('p.createdAt >= :date')->setParameter('date', $date)->getQuery()->getSingleScalarResult();
    }
}

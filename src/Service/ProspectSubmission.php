<?php

namespace App\Service;

use App\Entity\Prospect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProspectSubmission
{
    public function __construct(private EntityManagerInterface $manager, private ValidatorInterface $validator) {}

    public function submit(array $data): array
    {
        $values = [];
        $errors = [];
        foreach (['firstName', 'lastName', 'email', 'phone', 'message'] as $field) {
            $value = $data[$field] ?? '';
            if (!is_string($value)) $errors[$field] = 'Valeur invalide.';
            $values[$field] = is_string($value) ? $value : '';
        }
        if ($errors) return $errors;

        $prospect = new Prospect(...$values);
        foreach ($this->validator->validate($prospect) as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        if ($errors) return $errors;

        $this->manager->persist($prospect);
        $this->manager->flush();

        return [];
    }
}

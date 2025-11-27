<?php

declare(strict_types=1);

namespace App\Domain;

use InvalidArgumentException;

class User
{
    public function __construct(
        private readonly int $id,
        private readonly string $name
    ) {
        if ($id <= 0) {
            throw new InvalidArgumentException('L\'ID doit être positif');
        }

        $trimmedName = trim($name);
        if ($trimmedName === '') {
            throw new InvalidArgumentException('Le nom ne peut pas être vide');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}


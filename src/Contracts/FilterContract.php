<?php

declare(strict_types=1);

namespace App\Contracts;

use Doctrine\ORM\QueryBuilder;

interface FilterContract
{
    public function apply(QueryBuilder $qb, string $field, mixed $value, string $alias): void;

    public function validate(mixed $value): void;

    public function getName(): string;
}

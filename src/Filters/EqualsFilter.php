<?php

declare(strict_types=1);

namespace App\Filters;

use App\Contracts\FilterContract;
use Doctrine\ORM\QueryBuilder;

class EqualsFilter implements FilterContract
{
    public function apply(QueryBuilder $qb, string $field, mixed $value, string $alias): void
    {
        $paramName = $field . '_eq_' . uniqid();
        $qb->andWhere("$alias.$field = :$paramName")
            ->setParameter($paramName, $value);
    }

    public function validate(mixed $value): void
    {
    }

    public function getName(): string
    {
        return 'equals';
    }
}

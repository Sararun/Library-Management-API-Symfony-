<?php

declare(strict_types=1);

namespace App\Filters;

use App\Contracts\FilterContract;
use Doctrine\ORM\QueryBuilder;

class ContainsFilter implements FilterContract
{
    public function apply(QueryBuilder $qb, string $field, mixed $value, string $alias): void
    {
        $paramName = $field . '_contains_' . uniqid();
        $qb->andWhere("$alias.$field LIKE :$paramName")
            ->setParameter($paramName, '%' . $value . '%');
    }

    public function validate(mixed $value): void
    {
        if (!\is_string($value)) {
            throw new \InvalidArgumentException('Contains filter requires string value');
        }
    }

    public function getName(): string
    {
        return 'contains';
    }
}

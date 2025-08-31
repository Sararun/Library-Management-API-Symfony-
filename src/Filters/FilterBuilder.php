<?php

declare(strict_types=1);

namespace App\Filters;

use App\Attributes\FilterableField;
use Doctrine\ORM\QueryBuilder;

class FilterBuilder
{
    public function __construct(
        private FilterRegistry $filterRegistry,
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function buildFilters(QueryBuilder $qb, string $entityClass, array $filters, string $alias = 'e'): void
    {
        $reflection = new \ReflectionClass($entityClass);

        foreach ($filters as $filter) {
            $property = $reflection->getProperty($filter['field']);
            $filterableAttrs = $property->getAttributes(FilterableField::class);

            if (empty($filterableAttrs)) {
                throw new \InvalidArgumentException("Field {$filter['field']} is not filterable");
            }

            $filterableField = $filterableAttrs[0]->newInstance();
            $filterInstance = $this->filterRegistry->getByName($filter['type']);

            if (!\in_array($filterInstance::class, $filterableField->allowedFilters)) {
                throw new \InvalidArgumentException(
                    "Filter {$filter['type']} not allowed for field {$filter['field']}",
                );
            }

            $filterInstance->validate($filter['value']);
            $filterInstance->apply($qb, $filter['field'], $filter['value'], $alias);
        }
    }
}

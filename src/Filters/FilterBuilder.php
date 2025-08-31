<?php

declare(strict_types=1);

namespace App\Filters;

use App\Attributes\FilterableField;
use App\Dto\Requests\FilterDto;
use Doctrine\ORM\QueryBuilder;
use ReflectionException;

class FilterBuilder
{
    public function __construct(
        private FilterRegistry $filterRegistry,
    ) {
    }


    /**
     * @param QueryBuilder $qb
     * @param class-string $entityClass
     * @param FilterDto[] $filters
     * @param string $alias
     * @return void
     * @throws ReflectionException
     */
    public function buildFilters(QueryBuilder $qb, string $entityClass, array $filters, string $alias = 'e'): void
    {
        $reflection = new \ReflectionClass($entityClass);

        foreach ($filters as $filter) {
            $property = $reflection->getProperty($filter->field);
            $filterableAttrs = $property->getAttributes(FilterableField::class);

            if (empty($filterableAttrs)) {
                throw new \InvalidArgumentException("Поле {$filter['field']} не участвует в фильтрации");
            }

            $filterableField = $filterableAttrs[0]->newInstance();
            $filterInstance = $this->filterRegistry->getByName($filter->type);

            if (!\in_array($filterInstance::class, $filterableField->allowedFilters)) {
                throw new \InvalidArgumentException(
                    "Фильтр {$filter->type} не поддерживает поле {$filter->field}",
                );
            }

            $filterInstance->validate($filter->value);
            $filterInstance->apply($qb, $filter->field, $filter->value, $alias);
        }
    }
}

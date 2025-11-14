<?php

declare(strict_types=1);

namespace App\Filters;

use App\Contracts\FilterContract;

class FilterRegistry
{
    /** @var array<string, FilterContract> */
    private array $filters = [];

    /**
     * @throws \Exception
     */
    public function __construct(iterable $filters)
    {
        foreach ($filters as $filter) {
            if (isset($this->filters[$filter->getName()])) {
                throw new \Exception('Такой фильтр зарегистрирован');
            }
            $this->filters[$filter->getName()] = $filter;
        }
    }

    public function getByClass(string $filterClass): FilterContract
    {
        foreach ($this->filters as $filter) {
            if ($filter::class === $filterClass) {
                return $filter;
            }
        }

        throw new \RuntimeException("Filter not found: $filterClass");
    }

    public function getByName(string $name): FilterContract
    {
        if (!isset($this->filters[$name])) {
            throw new \RuntimeException("Filter not found: $name");
        }

        return $this->filters[$name];
    }
}

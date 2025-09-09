<?php

namespace App\Dto;

use ArrayIterator;
use Countable;

/**
 * @template T of object
 * @implements \IteratorAggregate<int, T>
 */
final readonly class PaginatedEntities implements \IteratorAggregate, Countable
{
    public int $pages;

    /**
     * @param T[] $items
     * @param int $total
     * @param int $page
     * @param int $limit
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $page,
        public int $limit,
    ) {
        $this->pages = (int)ceil($this->total / $this->limit);
    }


    public function hasNextPage(): bool
    {
        return $this->page < $this->pages;
    }


    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }


    public function getFrom(): int
    {
        return ($this->page - 1) * $this->limit + 1;
    }


    public function getTo(): int
    {
        return min($this->page * $this->limit, $this->total);
    }

    /**
     * @return \Traversable<int, T>
     */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
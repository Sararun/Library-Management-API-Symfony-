<?php

declare(strict_types=1);

namespace App\Contracts\Services\AuthorServices;

use App\Dto\PaginatedEntities;
use App\Dto\Requests\AuthorListRequest;
use App\Dto\Requests\FilterDto;
use App\Dto\Requests\SortDto;
use App\Entity\Author;
use Doctrine\Common\Collections\Order;

interface GetListAuthorsServiceContract
{
    /**
     * @param int $page
     * @param int $limit
     * @param FilterDto[] $filters
     * @param string $sortField
     * @param string $sortOrder
     * @return PaginatedEntities<Author>
     */
    public function handle(
        int $page,
        int $limit,
        array $filters,
        string $sortField = 'id',
        string $sortOrder = Order::Ascending->value
    ): PaginatedEntities;
}

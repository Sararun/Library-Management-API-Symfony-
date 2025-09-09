<?php

declare(strict_types=1);

namespace App\Services\AuthorServices;

use App\Contracts\Repositories\AuthorRepositoryContract;
use App\Contracts\Services\AuthorServices\GetListAuthorsServiceContract;
use App\Dto\PaginatedEntities;
use App\Dto\Requests\AuthorListRequest;
use App\Dto\Requests\SortDto;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\Order;

final readonly class GetListAuthorsService implements GetListAuthorsServiceContract
{
    public function __construct(private AuthorRepositoryContract $authorRepository)
    {
    }

    /**
     * @inheritDoc
    */
    public function handle(int $page, int $limit, array $filters, string $sortField = 'id', string $sortOrder = Order::Ascending->value): PaginatedEntities
    {
        return $this->authorRepository->getPaginatedAuthors($page,  $limit,  $filters, $sortField, $sortOrder);
    }
}

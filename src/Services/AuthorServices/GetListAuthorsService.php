<?php

declare(strict_types=1);

namespace App\Services\AuthorServices;

use App\Contracts\Services\AuthorServices\GetListAuthorsServiceContract;
use App\Dto\Requests\AuthorListRequest;
use App\Dto\Requests\SortDto;
use App\Repository\AuthorRepository;

final readonly class GetListAuthorsService implements GetListAuthorsServiceContract
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    /**
     * @inheritDoc
    */
    public function handle(int $page, int $limit, array $filters, ?SortDto $sort = null): array
    {
        return $this->authorRepository->getPaginatedAuthors($page,  $limit,  $filters, $sort);
    }
}

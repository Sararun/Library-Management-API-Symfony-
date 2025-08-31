<?php

namespace App\Services\AuthorServices;

use App\Contracts\Services\AuthorServices\GetListAuthorsServiceContract;
use App\Dto\Requests\AuthorListRequest;
use App\Repository\AuthorRepository;
use Exception;

final readonly class GetListAuthorsService implements GetListAuthorsServiceContract
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(AuthorListRequest $authorListRequest): array
    {
        return $this->authorRepository->getPaginatedAuthors($authorListRequest);
    }
}
<?php

declare(strict_types=1);

namespace App\Contracts\Services\AuthorServices;

use App\Dto\Requests\AuthorListRequest;
use App\Dto\Requests\FilterDto;
use App\Dto\Requests\SortDto;

interface GetListAuthorsServiceContract
{
    /**
     * @param int $page
     * @param int $limit
     * @param FilterDto[] $filters
     * @param SortDto|null $sort
     * @return array
     */
    public function handle(int $page, int $limit, array $filters, ?SortDto $sort): array;
}

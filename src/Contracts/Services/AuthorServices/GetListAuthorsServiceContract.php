<?php

declare(strict_types=1);

namespace App\Contracts\Services\AuthorServices;

use App\Dto\Requests\AuthorListRequest;

interface GetListAuthorsServiceContract
{
    public function handle(AuthorListRequest $request): array;
}

<?php

namespace App\Contracts\Services\AuthorServices;

use App\Dto\Requests\AuthorListRequest;

interface GetListAuthorsServiceContract
{
    public function handle(AuthorListRequest $request): array;

}
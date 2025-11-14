<?php

declare(strict_types=1);

namespace App\Dto\Requests;

use App\Entity\Author;

class AuthorListRequest extends AbstractFilterableListRequest
{
    public function getEntityClass(): string
    {
        return Author::class;
    }
}

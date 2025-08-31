<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Contracts\FilterContract;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class FilterableField
{
    /**
     * @param array<class-string<FilterContract>> $allowedFilters
     * @param mixed[] $validationRules
     */
    public function __construct(
        public array $allowedFilters = [],
        public array $validationRules = [],
    ) {
    }
}

<?php

namespace App\Attributes;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class SortableField
{
    public function __construct(
        public bool $sortable = true,
        public ?string $customField = null
    ) {}
}
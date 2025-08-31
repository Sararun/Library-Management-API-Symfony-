<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Validators\ValidEntityFiltersValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ValidEntityFilters extends Constraint
{
    public function __construct(
        public string $entityClass,
        public string $message = 'Invalid filter: field "{{ field }}" with type "{{ type }}" is not allowed for entity {{ entity }}',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return ValidEntityFiltersValidator::class;
    }
}

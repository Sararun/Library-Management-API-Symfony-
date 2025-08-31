<?php

namespace App\Attributes;

use App\Validators\ValidEntitySortValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ValidEntitySort extends Constraint
{
    public function __construct(
        public string $entityClass,
        public string $message = 'Поле "{{ field }}" не имеет сортировки для {{ entity }}',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return ValidEntitySortValidator::class;
    }
}
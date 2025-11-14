<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Validators\ValidEntityFiltersValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ValidEntityFilters extends Constraint
{
    /**
     * @param string $entityClass
     * @param string $message
     * @param mixed|null $options
     * @param string[]|null $groups
     * @param mixed|null $payload
     */
    public function __construct(
        public string $entityClass,
        public string $message = 'Недопустимый фильтр: поле «{{ field }}» типа «{{ type }}» не допускается для сущности {{ entity }}',
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

<?php

declare(strict_types=1);

namespace App\Validators;

use App\Attributes\FilterableField;
use App\Attributes\ValidEntityFilters;
use App\Dto\Requests\FilterDto;
use App\Filters\FilterRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidEntityFiltersValidator extends ConstraintValidator
{
    public function __construct(
        private FilterRegistry $filterRegistry,
    ) {
    }

    /**
     * @param mixed | array<int, FilterDto> $value
     * @param Constraint $constraint
     * @return void
     * @throws \ReflectionException
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidEntityFilters) {
            throw new UnexpectedTypeException($constraint, ValidEntityFilters::class);
        }

        if (!\is_array($value)) {
            return;
        }

        $allowedFilters = $this->getAllowedFilters($constraint->entityClass);

        foreach ($value as $index => $filter) {
           if (! $filter instanceof FilterDto) {
               continue;
           }

            $field = $filter->field;
            $type = $filter->type;

            // Проверяем, существует ли поле
            if (!isset($allowedFilters[$field])) {
                $this->context->buildViolation('Поле "{{ field }}" не может использоваться в фильтрации')
                    ->setParameter('{{ field }}', $field)
                    ->atPath("[$index][field]")
                    ->addViolation();
                continue;
            }

            // Проверяем, разрешён ли тип фильтра для поля
            $allowedTypes = $allowedFilters[$field];
            if (!\in_array($type, $allowedTypes)) {
                $this->context->buildViolation('Фильтр типа "{{ type }}" не поддерживается для поля "{{ field }}". Поддерживается следующие: {{ allowed }}')
                    ->setParameter('{{ type }}', $type)
                    ->setParameter('{{ field }}', $field)
                    ->setParameter('{{ allowed }}', implode(', ', $allowedTypes))
                    ->atPath("[$index][type]")
                    ->addViolation();
            }
        }
    }

    /**
     * @param class-string $entityClass
     *
     * @throws \ReflectionException
     */
    private function getAllowedFilters(string $entityClass): array
    {
        $reflection = new \ReflectionClass($entityClass);
        $allowedFilters = [];

        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(FilterableField::class);

            if (empty($attributes)) {
                continue;
            }

            $filterableField = $attributes[0]->newInstance();
            $allowedTypes = [];

            foreach ($filterableField->allowedFilters as $filterClass) {
                $filter = $this->filterRegistry->getByClass($filterClass);
                $allowedTypes[] = $filter->getName();
            }

            $allowedFilters[$property->getName()] = $allowedTypes;
        }
        return $allowedFilters;
    }
}

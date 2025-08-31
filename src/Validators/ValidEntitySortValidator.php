<?php

namespace App\Validators;

use App\Attributes\SortableField;
use App\Attributes\ValidEntitySort;
use App\Dto\Requests\SortDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidEntitySortValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidEntitySort) {
            return;
        }

        if (!$value instanceof SortDto) {
            return;
        }

        $sortableFields = $this->getSortableFields($constraint->entityClass);

        if (!in_array($value->field, $sortableFields)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $value->field)
                ->setParameter('{{ entity }}', $constraint->entityClass)
                ->addViolation();
        }
    }

    private function getSortableFields(string $entityClass): array
    {
        $reflection = new \ReflectionClass($entityClass);
        $sortableFields = [];

        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(SortableField::class);

            if (!empty($attributes)) {
                $sortableField = $attributes[0]->newInstance();
                if ($sortableField->sortable) {
                    $sortableFields[] = $property->getName();
                }
            }
        }

        return $sortableFields;
    }
}
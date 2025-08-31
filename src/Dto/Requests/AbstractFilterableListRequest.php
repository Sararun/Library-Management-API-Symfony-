<?php

declare(strict_types=1);

namespace App\Dto\Requests;

use App\Attributes\ValidEntityFilters;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractFilterableListRequest
{
    #[Assert\Positive(message: 'Page должна быть больше 0')]
    public int $page = 1;

    #[Assert\Range(
        notInRangeMessage: 'Limit должен быть от {{ min }} до {{ max }}',
        min: 1,
        max: 50,
    )]
    public int $limit = 10;

    /**
     * @var array<int, array{field: string, type: string, value: mixed}>
     */
    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'field' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(),
                ],
                'type' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(),
                ],
                'value' => [
                    new Assert\NotNull(),
                ],
            ],
            'allowExtraFields' => false,
            'allowMissingFields' => false,
        ]),
    ])]
    public array $filters = [];

    abstract public function getEntityClass(): string;

    #[Assert\Callback]
    public function validateFilters(ExecutionContextInterface $context): void
    {
        $validator = $context->getValidator();
        $violations = $validator->validate(
            $this->filters,
            new ValidEntityFilters(entityClass: $this->getEntityClass()),
        );

        foreach ($violations as $violation) {
            $context->buildViolation($violation->getMessage())
                ->atPath('filters' . $violation->getPropertyPath())
                ->addViolation();
        }
    }
}

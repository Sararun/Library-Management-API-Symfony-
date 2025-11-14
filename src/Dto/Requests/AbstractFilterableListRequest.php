<?php

declare(strict_types=1);

namespace App\Dto\Requests;

use App\Attributes\ValidEntityFilters;
use App\Attributes\ValidEntitySort;
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
     * @var array<int, FilterDto>
     */
    #[Assert\Valid]
    public array $filters = [];

    public SortDto $sort;

    public function __construct()
    {
        $this->sort = new SortDto();
    }

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

    #[Assert\Callback]
    public function validateSort(ExecutionContextInterface $context): void
    {
        $violations = $context->getValidator()->validate(
            $this->sort,
            new ValidEntitySort(entityClass: $this->getEntityClass())
        );

        foreach ($violations as $violation) {
            $context->buildViolation($violation->getMessage())
                ->atPath('sort')
                ->addViolation();
        }
    }
}

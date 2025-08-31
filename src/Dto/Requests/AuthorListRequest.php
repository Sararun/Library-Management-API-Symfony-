<?php

namespace App\Dto\Requests;
use App\Enums\FiltersTypes;
use Symfony\Component\Validator\Constraints as Assert;


class AuthorListRequest
{
    #[Assert\Positive(message: 'Page должна быть больше 0')]
    public int $page = 1;

    #[Assert\Range(
        notInRangeMessage: 'Limit должен быть от {{ min }} до {{ max }}',
        min: 1,
        max: 50
    )]
    public int $limit = 10;

    /**
     * @var array<int, array{name: string, value: string, type: string}>
     */
    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'name' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(),
                ],
                'value' => [
                    new Assert\NotNull(),
                ],
                'type' => [
                    new Assert\Type('string'),
                    new Assert\Choice([FiltersTypes::EQUAL->name, FiltersTypes::LIKE->name]),
                ],
            ],
            'allowExtraFields' => false,
            'allowMissingFields' => false,
        ])
    ])]
    public array $filters = [];

}
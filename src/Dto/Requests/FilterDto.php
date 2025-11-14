<?php

namespace App\Dto\Requests;
use Symfony\Component\Validator\Constraints as Assert;

class FilterDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public string $field;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public string $type;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public mixed $value;
}
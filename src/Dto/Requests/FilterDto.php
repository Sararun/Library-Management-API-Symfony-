<?php

namespace App\Dto\Requests;
use Symfony\Component\Validator\Constraints as Assert;

class FilterDto
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $field;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $type;

    #[Assert\NotNull]
    public mixed $value;
}
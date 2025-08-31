<?php

namespace App\Dto\Requests;
use Doctrine\Common\Collections\Order;
use Symfony\Component\Validator\Constraints as Assert;


class SortDto
{
    #[Assert\Type('string')]
    public string $field = 'id';


    #[Assert\Choice(
        choices: [Order::Ascending->value, Order::Descending->value],
        message: 'Сортировка может быть ASC или DESC'
    )]
    public string $order = 'ASC';
}
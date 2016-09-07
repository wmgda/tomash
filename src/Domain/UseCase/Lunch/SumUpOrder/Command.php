<?php

namespace Domain\UseCase\Lunch\SumUpOrder;

class Command
{
    public function __construct(string $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function getRestaurant() : string
    {
        return $this->restaurant;
    }
}

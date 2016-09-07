<?php

declare(strict_types = 1);

namespace Domain\UseCase\Lunch\InitializeOrder;

class Command
{
    /**
     * @var string
     */
    private $restaurant;

    /**
     * @param string $restaurant
     */
    public function __construct(string $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return string
     */
    public function getRestaurant() : string
    {
        return $this->restaurant;
    }
}

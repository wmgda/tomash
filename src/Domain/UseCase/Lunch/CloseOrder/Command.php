<?php

namespace Domain\UseCase\Lunch\CloseOrder;

class Command
{
    /**
     * @var string
     */
    private $restaurant;

    /**
     * @param string $restaurant
     */
    public function __construct($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return string
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }
}

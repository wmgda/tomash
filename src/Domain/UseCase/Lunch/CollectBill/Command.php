<?php

namespace Domain\UseCase\Lunch\CollectBill;

class Command
{
    /**
     * @var string
     */
    private $restaurant;

    /**
     * @var string
     */
    private $user;

    /**
     * @param string $restaurant
     * @param string $user
     */
    public function __construct(string $restaurant, string $user)
    {
        $this->restaurant = $restaurant;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getRestaurant() : string
    {
        return $this->restaurant;
    }

    /**
     * @return string
     */
    public function getUser() : string
    {
        return $this->user;
    }
}

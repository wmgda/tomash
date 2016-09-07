<?php

namespace Domain\UseCase\Lunch\AddItemToOrder;

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
     * @var string
     */
    private $position;

    /**
     * @param string $restaurant
     * @param string $user
     * @param string $position
     */
    public function __construct(string $restaurant, string $user, string $position)
    {
        $this->restaurant = $restaurant;
        $this->user = $user;
        $this->position = $position;
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}

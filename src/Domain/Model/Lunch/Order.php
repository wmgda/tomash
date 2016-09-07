<?php

namespace Domain\Model\Lunch;

class Order
{
    /**
     * @var Restaurant
     */
    private $restaurant;

    /**
     * @var Participant[]
     */
    private $participants;

    /**
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->participants = [];
    }

    /**
     * @return Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @return Participant[]
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param string $participantName
     * @param string $position
     */
    public function add($participantName, $position)
    {
        // AddItemToOrder use case
    }
}

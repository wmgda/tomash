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
     * @param MenuItem $menuItem
     */
    public function add(string $participantName, MenuItem $menuItem)
    {
        $this->createParticipantIfNotExists($participantName);
        $this->participants[$participantName]->addItem($menuItem);
    }

    /**
     * @param $participantName
     */
    private function createParticipantIfNotExists($participantName)
    {
        if(!array_key_exists($participantName, $this->participants)) {
            $this->participants[$participantName] = new Participant($participantName);
        }
    }
}

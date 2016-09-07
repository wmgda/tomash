<?php

namespace Domain\Model\Lunch;

class Participant
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var MenuItem[]
     */
    private $items;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->items = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return MenuItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param MenuItem $position
     */
    public function addItem(MenuItem $position)
    {
        $this->items[] = $position;
    }
}
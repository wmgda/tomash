<?php

namespace Domain\Model\Lunch;

class MenuItem
{
    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $name;

    /**
     * @var MenuItemPrice
     */
    private $price;

    /**
     * @param string $position
     * @param string $name
     * @param MenuItemPrice $price
     */
    public function __construct($position, $name, MenuItemPrice $price)
    {
        $this->position = $position;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return MenuItemPrice
     */
    public function getPrice()
    {
        return $this->price;
    }
}

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
     * @var float
     */
    private $sum;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->items = [];
        $this->sum = 0.0;
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
        $this->increaseSum($position->getPrice()->toFloat());
    }

    /**
     * @param float $price
     */
    public function increaseSum(float $price)
    {
        $this->sum += $price;
    }

    /**
     * @return float
     */
    public function getSum() : float
    {
        return $this->sum;
    }
}
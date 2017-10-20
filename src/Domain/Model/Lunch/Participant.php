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
     * @var array
     */
    private $annotations;

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
        $this->annotations = [];
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
     * @param string $position
     *
     * @return string
     */
    public function getItemAnnotation(string $position)
    {
        if (!array_key_exists($position, $this->annotations)) {
            return "";
        }

        return $this->annotations[$position];
    }

    /**
     * @param MenuItem $position
     * @param string $annotation
     */
    public function addItem(MenuItem $position, string $annotation)
    {
        $this->items[] = $position;
        $this->annotations[$position->getPosition()] = $annotation;
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
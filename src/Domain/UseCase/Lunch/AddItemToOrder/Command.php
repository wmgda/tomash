<?php

namespace Domain\UseCase\Lunch\AddItemToOrder;

use Domain\Model\Lunch\Order;

class Command
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $position;

    /**
     * @param Order $order
     * @param string $user
     * @param string $position
     */
    public function __construct(Order $order, string $user, string $position)
    {
        $this->order = $order;
        $this->user = $user;
        $this->position = $position;
    }

    /**
     * @return Order
     */
    public function getOrder() : Order
    {
        return $this->order;
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

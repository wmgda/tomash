<?php

namespace Domain\UseCase\Lunch\CollectBill;

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
     * @param Order $order
     * @param string $user
     */
    public function __construct(Order $order, string $user)
    {
        $this->order = $order;
        $this->user = $user;
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
    public function getUser() : string
    {
        return $this->user;
    }
}

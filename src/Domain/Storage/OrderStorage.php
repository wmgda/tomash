<?php

namespace Domain\Storage;

use Domain\Model\Lunch\Order;

interface OrderStorage
{
    /**
     * @param Order $order
     */
    public function save(Order $order);

    /**
     * @param string $restaurantName
     */
    public function load(string $restaurantName) : Order;

    /**
     * @param string $restaurantName
     */
    public function drop(string $restaurantName);
}

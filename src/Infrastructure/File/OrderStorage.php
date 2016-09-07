<?php

namespace Infrastructure\File;

use Domain\Model\Lunch\Order;

class OrderStorage implements \Domain\Storage\OrderStorage
{
    private static $path = './var/storage/orders/';

    public function save(Order $order)
    {
        $file = static::$path . $order->getRestaurant()->getName();

        file_put_contents($file, serialize($order));
    }

    public function load(string $restaurantName)
    {
        $file = static::$path . $restaurantName;

        if(!file_exists($file)) {
            throw new \Exception(sprintf("Order from restaurant %s does not exist", $restaurantName));
        }

        return unserialize(file_get_contents($file));
    }

    public function drop(string $restaurantName)
    {
        $file = static::$path . $restaurantName;

        unlink($file);
    }

}

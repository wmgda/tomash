<?php

namespace Domain\UseCase\Lunch;

use Domain\Exception\NotSupportedRestaurantException;
use Domain\Model\Lunch\Order;
use Domain\Model\Lunch\Restaurant;
use Domain\UseCase\Lunch\InitializeOrder\Command;
use Domain\UseCase\Lunch\InitializeOrder\Responder;

class InitializeOrder
{
    private static $supportedRestaurants = [
        'adong' => "A'Dong"
    ];

    /**
     * @var Order
     */
    private $order;

    /**
     * @param Command $command
     * @param Responder $responder
     */
    public function execute(Command $command, Responder $responder)
    {
        $restaurantName = $command->getRestaurant();

        try {
            $this->validateRestaurant($restaurantName);
            $restaurant = new Restaurant(
                $restaurantName,
                static::$supportedRestaurants[$restaurantName]
            );
            $this->order = new Order($restaurant);
        } catch (\Exception $e) {
            $responder->orderInitializationFailed($e);
        }

        $responder->orderInitializedSuccessfully($this->order);
    }

    /**
     * @param $restaurantName
     * @throws NotSupportedRestaurantException
     */
    private function validateRestaurant($restaurantName)
    {
        if(!in_array($restaurantName, array_keys(static::$supportedRestaurants))) {
            throw new NotSupportedRestaurantException($restaurantName, array_keys(static::$supportedRestaurants));
        }
    }
}

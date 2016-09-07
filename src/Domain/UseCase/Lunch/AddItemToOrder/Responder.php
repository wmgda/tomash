<?php

namespace Domain\UseCase\Lunch\AddItemToOrder;

use Domain\Model\Lunch\MenuItem;
use Domain\Model\Lunch\Order;

interface Responder
{
    /**
     * @param Order $order
     * @param string $userName
     * @param MenuItem $addedMenuItem
     */
    public function successfullyAddedItemToOrder(Order $order, string $userName, MenuItem $addedMenuItem);

    /**
     * @param \Exception $e
     */
    public function addingItemToOrderFailed(\Exception $e);
}

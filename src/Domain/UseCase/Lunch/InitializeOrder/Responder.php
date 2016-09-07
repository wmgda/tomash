<?php

namespace Domain\UseCase\Lunch\InitializeOrder;

use Domain\Model\Lunch\Order;

interface Responder
{
    /**
     * @param Order $order
     */
    public function orderInitializedSuccessfully(Order $order);

    /**
     * @param \Exception $e
     */
    public function orderInitializationFailed(\Exception $e);
}

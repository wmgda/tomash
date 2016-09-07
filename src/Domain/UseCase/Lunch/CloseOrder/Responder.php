<?php

namespace Domain\UseCase\Lunch\CloseOrder;

interface Responder
{
    /**
     * @param string $restaurantName
     */
    public function orderClosedSuccessfully(string $restaurantName);

    /**
     * @param \Exception $e
     */
    public function closingOrderFailed(\Exception $e);
}

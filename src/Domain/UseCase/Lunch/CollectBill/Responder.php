<?php

namespace Domain\UseCase\Lunch\CollectBill;

use Domain\Model\Lunch\Order;
use Domain\Model\Lunch\Participant;

interface Responder
{
    /**
     * @param Order $order
     * @param float $totalSum
     */
    public function billCollectedSuccessfully(Order $order, float $totalSum);

    /**
     * @param \Exception $e
     */
    public function collectingBillFailed(\Exception $e);
}

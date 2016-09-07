<?php

namespace Domain\UseCase\Lunch\SumUpOrder;

use Domain\Model\Lunch\Order;

interface Responder
{
    public function successfullySummedUpOrder(Order $order, array $items);

    public function sumUpOrderFailed(\Exception $e);
}

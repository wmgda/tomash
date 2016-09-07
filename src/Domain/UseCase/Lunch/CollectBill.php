<?php

namespace Domain\UseCase\Lunch;

use Domain\Model\Lunch\Order;
use Domain\Storage\OrderStorage;
use Domain\UseCase\Lunch\CollectBill\Command;
use Domain\UseCase\Lunch\CollectBill\Responder;

class CollectBill
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var OrderStorage
     */
    private $storage;

    /**
     * @param OrderStorage $storage
     */
    public function __construct(OrderStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Command $command
     * @param Responder $responder
     */
    public function execute(Command $command, Responder $responder)
    {
        $this->order = $this->storage->load($command->getRestaurant());

        $totalSum = 0.0;
        try {
            foreach($this->order->getParticipants() as $participant) {
                $totalSum += $participant->getSum();
            }
        } catch (\Exception $e) {
            $responder->collectingBillFailed($e);
        }

        $responder->billCollectedSuccessfully($this->order, $totalSum);
    }
}
<?php

namespace Domain\UseCase\Lunch;

use Domain\Model\Lunch\OrderedMenuItem;
use Domain\Model\Lunch\SummaryItem;
use Domain\Storage\OrderStorage;
use Domain\UseCase\Lunch\SumUpOrder\Command;
use Domain\UseCase\Lunch\SumUpOrder\Responder;

class SumUpOrder
{
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

    public function execute(Command $command, Responder $responder)
    {
        try {
            $order = $this->storage->load($command->getRestaurant());
            $items = [];

            foreach ($order->getParticipants() as $participant) {
                foreach ($participant->getItems() as $item) {
                    $items[] = new OrderedMenuItem($item, $participant);
                }
            }

            /**
             * @var $summedItems SummaryItem[]
             */
            $summedItems = [];

            /** @var OrderedMenuItem $item */
            foreach ($items as $item) {
                if (!array_key_exists($item->getItem()->getPosition(), $summedItems)) {
                    $summedItems[$item->getItem()->getPosition()] = new SummaryItem($item);
                }

                $summedItems[$item->getItem()->getPosition()]->increase();
                $summedItems[$item->getItem()->getPosition()]->addPurchaser($item->getParticipant());
            }
        } catch (\Exception $e) {
            $responder->sumUpOrderFailed($e);
        }


        $responder->successfullySummedUpOrder($order, $summedItems);
    }
}

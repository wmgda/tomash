<?php

namespace Domain\UseCase\Lunch;

use Domain\Model\Lunch\MenuItem;
use Domain\Model\Lunch\OrderedMenuItem;
use Domain\Model\Lunch\Participant;
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
            $summedItems = [];

            foreach ($order->getParticipants() as $participant) {
                foreach ($participant->getItems() as $item) {
                    $items[] = new OrderedMenuItem($item, $participant);
                }
            }

            /** @var OrderedMenuItem $item */
            foreach ($items as $item) {
                if (!array_key_exists($item->getItem()->getPosition(), $summedItems)) {
                    $summedItems[$item->getItem()->getPosition()] = [
                        'qty' => 0,
                        'item' => $item->getItem(),
                        'purchasers' => []
                    ];
                }

                $summedItems[$item->getItem()->getPosition()]['qty'] = $summedItems[$item->getItem()->getPosition()]['qty'] + 1;
                $summedItems[$item->getItem()->getPosition()]['purchasers'][] = $item->getParticipant()->getName();
            }
        } catch (\Exception $e) {
            $responder->sumUpOrderFailed($e);
        }


        $responder->successfullySummedUpOrder($order, $summedItems);
    }
}

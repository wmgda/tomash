<?php

namespace Domain\UseCase\Lunch;

use Domain\Model\Lunch\MenuItem;
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
                    $items[] = $item;
                }
            }

            /** @var MenuItem $item */
            foreach ($items as $item) {
                if (!array_key_exists($item->getPosition(), $summedItems)) {
                    $summedItems[$item->getPosition()] = ['qty' => 0, 'item' => $item];
                }

                $summedItems[$item->getPosition()]['qty'] = $summedItems[$item->getPosition()]['qty'] + 1;
            }
        } catch (\Exception $e) {
            $responder->sumUpOrderFailed($e);
        }


        $responder->successfullySummedUpOrder($order, $summedItems);
    }
}

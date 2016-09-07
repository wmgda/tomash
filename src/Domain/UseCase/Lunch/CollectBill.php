<?php

namespace Domain\UseCase\Lunch;

use Domain\Exception\ParticipantDoesNotExistException;
use Domain\Model\Lunch\Order;
use Domain\Model\Lunch\Participant;
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
     * @var Participant
     */
    private $participant;

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
        $user = $command->getUser();

        try {
            $this->setParticipant($user);
            $totalSum = 0.0;
            foreach($this->participant->getItems() as $menuItem) {
                $totalSum += $menuItem->getPrice()->toFloat();
            }
        } catch (\Exception $e) {
            $responder->collectingBillFailed($e);
        }

        $responder->billCollectedSuccessfully($this->order, $this->participant, $totalSum);
    }

    /**
     * @param string $user
     */
    private function setParticipant(string $user)
    {
        if (!array_key_exists($user, $this->order->getParticipants())) {
            throw new ParticipantDoesNotExistException($user);
        }

        $this->participant = $this->order->getParticipants()[$user];
    }
}

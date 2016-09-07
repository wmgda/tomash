<?php

namespace Domain\UseCase\Lunch;

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
        $order = $this->storage->load($command->getRestaurant());

        var_dump($order);
    }
}

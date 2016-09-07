<?php

namespace Domain\UseCase\Lunch;

use Domain\Storage\OrderStorage;
use Domain\UseCase\Lunch\CloseOrder\Command;
use Domain\UseCase\Lunch\CloseOrder\Responder;

class CloseOrder
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

    }
}

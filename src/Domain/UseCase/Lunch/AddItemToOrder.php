<?php

namespace Domain\UseCase\Lunch;

use Domain\Exception\PositionDoesNotExistInMenuException;
use Domain\Model\Lunch\MenuItem;
use Domain\Model\Lunch\Order;
use Domain\Storage\OrderStorage;
use Domain\UseCase\Lunch\AddItemToOrder\Command;
use Domain\UseCase\Lunch\AddItemToOrder\Responder;

class AddItemToOrder
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var MenuItem
     */
    private $menuItem;

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
            $this->order = $this->storage->load($command->getRestaurant());

            $this->setMenuItem($command->getPosition());
            $this->order->add($command->getUser(), $this->menuItem, $command->getAnnotation());
            $this->storage->save($this->order);
        } catch (\Exception $e) {
            $responder->addingItemToOrderFailed($e);
        }

        $responder->successfullyAddedItemToOrder(
            $this->order,
            $command->getUser(),
            $this->menuItem,
            $command->getAnnotation()
        );
    }

    /**
     * @param string $position
     * @throws PositionDoesNotExistInMenuException
     */
    private function setMenuItem(string $position)
    {
        if (!array_key_exists($position, $this->order->getRestaurant()->getMenu())) {
            throw new PositionDoesNotExistInMenuException($position);
        }

        $this->menuItem = $this->order->getRestaurant()->getMenu()[$position];
    }
}

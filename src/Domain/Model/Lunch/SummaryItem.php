<?php

declare(strict_types = 1);

namespace Domain\Model\Lunch;

class SummaryItem
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @var OrderedMenuItem
     */
    private $orderedMenuItem;

    /**
     * @var Participant[]
     */
    private $purchasers;

    /**
     * @param OrderedMenuItem $orderedMenuItem
     */
    public function __construct(OrderedMenuItem $orderedMenuItem)
    {
        $this->quantity = 0;
        $this->orderedMenuItem = $orderedMenuItem;
        $this->purchasers = [];
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return OrderedMenuItem
     */
    public function getOrderedMenuItem(): OrderedMenuItem
    {
        return $this->orderedMenuItem;
    }

    /**
     * @return Participant[]
     */
    public function getPurchasers(): array
    {
        return $this->purchasers;
    }

    /**
     * @param Participant $participant
     */
    public function addPurchaser(Participant $participant)
    {
        $this->purchasers[] = $participant;
    }

    public function increase()
    {
        $this->quantity++;
    }
}

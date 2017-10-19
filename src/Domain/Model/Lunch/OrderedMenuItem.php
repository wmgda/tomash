<?php

declare(strict_types = 1);

namespace Domain\Model\Lunch;

class OrderedMenuItem
{
    /**
     * @var MenuItem
     */
    private $item;

    /**
     * @var Participant
     */
    private $participant;

    /**
     * @param MenuItem $item
     * @param Participant $participant
     */
    public function __construct(MenuItem $item, Participant $participant)
    {
        $this->item = $item;
        $this->participant = $participant;
    }

    /**
     * @return MenuItem
     */
    public function getItem(): MenuItem
    {
        return $this->item;
    }

    /**
     * @return Participant
     */
    public function getParticipant(): Participant
    {
        return $this->participant;
    }
}

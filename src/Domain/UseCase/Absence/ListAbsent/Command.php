<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\ListAbsent;

class Command
{
    /**
     * @var string
     */
    private $date;

    /**
     * Command constructor.
     * @param $person
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date->format('d-m-Y');
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }
}

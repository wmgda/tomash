<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\WhereIs;

class Command
{
    /**
     * @var string
     */
    private $person;

    /**
     * @var string
     */
    private $date;

    /**
     * Command constructor.
     * @param $person
     */
    public function __construct(\DateTime $date, string $person)
    {
        $this->person = $person;
        $this->date = $date->format('d-m-Y');
    }

    /**
     * @return string
     */
    public function getPerson(): string
    {
        return $this->person;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }
}

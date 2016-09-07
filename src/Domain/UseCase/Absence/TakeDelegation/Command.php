<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\TakeDelegation;

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
     * @param string $person
     * @param string $date
     */
    public function __construct(string $person, string $date)
    {
        $this->person = $person;
        $this->date = $date;
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

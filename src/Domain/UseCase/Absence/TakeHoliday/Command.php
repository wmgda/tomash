<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\TakeHoliday;

class Command
{
    /**
     * @var string
     */
    private $person;

    /**
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * Command constructor.
     * @param string $person
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     */
    public function __construct(string $person, \DateTime $dateStart, \DateTime $dateEnd)
    {
        $this->person = $person;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return string
     */
    public function getPerson(): string
    {
        return $this->person;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }
}

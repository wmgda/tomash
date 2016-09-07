<?php

namespace Domain\Model\Lunch;

class MenuItemPrice
{
    /**
     * @var int
     */
    private $zl;

    /**
     * @var int
     */
    private $gr;

    /**
     * @param int $zl
     * @param int $gr
     */
    public function __construct(int $zl, int $gr)
    {
        $this->zl = $zl;
        $this->gr = $gr;
    }

    /**
     * @return int
     */
    public function getZl()
    {
        return $this->zl;
    }

    /**
     * @return int
     */
    public function getGr()
    {
        return $this->gr;
    }

    /**
     * @return float
     */
    public function toFloat()
    {
        return floatval($this->zl . '.' . $this->gr);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->zl . ',' . $this->gr . ' zł';
    }
}

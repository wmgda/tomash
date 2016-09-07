<?php

namespace Domain\Exception;

class PositionDoesNotExistInMenuException extends \InvalidArgumentException
{
    public function __construct(string $position)
    {
        parent::__construct(sprintf(
            "Given position %s does not exist in restaurant's menu",
            $position
        ));
    }
}

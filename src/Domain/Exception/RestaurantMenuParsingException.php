<?php

namespace Domain\Exception;

class RestaurantMenuParsingException extends \Exception
{
    public function __construct(string $menuFilePath)
    {
        parent::__construct(sprintf(
            "Error occurred while parsing restaurant's menu in path: %s",
            $menuFilePath
        ));
    }
}

<?php

namespace Domain\Exception;

class RestaurantMenuFileNotFoundException extends \Exception
{
    public function __construct(string $menuFilePath)
    {
        parent::__construct(sprintf(
            "Could not find restaurant's menu in path: %s",
            $menuFilePath
        ));
    }
}

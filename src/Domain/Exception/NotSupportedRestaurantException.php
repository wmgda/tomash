<?php

namespace Domain\Exception;

class NotSupportedRestaurantException extends \InvalidArgumentException
{
    public function __construct(string $provided, array $supported)
    {
        parent::__construct(sprintf(
            "Not supported restaurant, given %s expected one of %s",
            $provided,
            implode(', ', $supported)
        ));
    }
}

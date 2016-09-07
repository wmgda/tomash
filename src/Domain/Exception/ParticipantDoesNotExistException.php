<?php

namespace Domain\Exception;

class ParticipantDoesNotExistException extends \InvalidArgumentException
{
    public function __construct(string $user)
    {
        parent::__construct(sprintf(
            "No such participant in order: %s",
            $user
        ));
    }
}

<?php

namespace AppBundle\SlackCommand\Jemy;

use AppBundle\SlackCommand\AbstractCommand;
use Domain\UseCase\Lunch\CloseOrder;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class ZamknijCommand extends AbstractCommand implements CloseOrder\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $regex = Regex::match('/zamknij (\w+)/', $message);

        if ($regex->hasMatch()) {


            return true;
        }

        return false;
    }

    public function closeOrder()
    {
        $command = new CloseOrder\Command($restaurant);

        $useCase = new CloseOrder(new OrderStorage());
        $useCase->execute($command, $this);
    }
}

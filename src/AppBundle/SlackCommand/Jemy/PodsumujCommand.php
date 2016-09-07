<?php

namespace AppBundle\SlackCommand\Jemy;

use AppBundle\SlackCommand\AbstractCommand;
use Domain\UseCase\Lunch\SumUpOrder;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class PodsumujCommand extends AbstractCommand implements SumUpOrder\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $sumUpOrderRegex = Regex::match('/podsumuj (.+)/', $message);

        if ($sumUpOrderRegex->hasMatch()) {
            $this->sumUpOrder();

            return true;
        }

        return false;
    }

    protected function sumUpOrder()
    {
        $command = new SumUpOrder\Command();

        $useCase = new SumUpOrder();
        $useCase->execute($command, $this);
    }
}

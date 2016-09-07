<?php

namespace AppBundle\SlackCommand\Jemy;

use AppBundle\SlackCommand\AbstractCommand;
use Domain\Model\Lunch\Order;
use Domain\UseCase\Lunch\SumUpOrder;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\Message\MessageBuilder;
use Slack\User;
use Spatie\Regex\Regex;

class PodsumujCommand extends AbstractCommand implements SumUpOrder\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $regex = Regex::match('/podsumuj (.+)/', $message);

        if ($regex->hasMatch()) {
            $this->sumUpOrder($regex->group(1));

            return true;
        }

        return false;
    }

    protected function sumUpOrder(string $restaurant)
    {
        $command = new SumUpOrder\Command($restaurant);

        $useCase = new SumUpOrder(new OrderStorage());
        $useCase->execute($command, $this);
    }

    public function successfullySummedUpOrder(Order $order)
    {
        $this->advancedReply(function (MessageBuilder $builder) {
            return $builder;
        });
    }

    public function sumUpOrderFailed(\Exception $e)
    {
        $this->reply('nie udało się podsumować zamówienia');
    }
}

<?php

namespace AppBundle\SlackCommand\Jemy;

use AppBundle\SlackCommand\AbstractCommand;
use Domain\Model\Lunch\Order;
use Domain\Model\Lunch\Participant;
use Domain\UseCase\Lunch\CollectBill;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\Message\MessageBuilder;
use Slack\User;
use Spatie\Regex\Regex;

class WindykujCommand extends AbstractCommand implements CollectBill\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $regex = Regex::match('/windykuj (.+)/', $message);

        if ($regex->hasMatch()) {
            $this->collectBill($regex->group(1));

            return true;
        }

        return false;
    }

    protected function collectBill(string $restaurant)
    {
        $command = new CollectBill\Command($restaurant, $this->user->getId());

        $useCase = new CollectBill(new OrderStorage());
        $useCase->execute($command, $this);
    }

    public function billCollectedSuccessfully(Order $order, Participant $participant, float $totalSum)
    {
        $this->advancedReply(function (MessageBuilder $builder) {
            return $builder;
        });
    }

    public function collectingBillFailed(\Exception $e)
    {
        $this->reply('coś się zepsuło i nie działa');
    }
}
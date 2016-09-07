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
            $this->closeOrder($regex->group(1));

            return true;
        }

        return false;
    }

    public function closeOrder(string $restaurant)
    {
        $command = new CloseOrder\Command($restaurant);

        $useCase = new CloseOrder(new OrderStorage());
        $useCase->execute($command, $this);
    }

    /**
     * @param string $restaurantName
     */
    public function orderClosedSuccessfully(string $restaurantName)
    {
        $this->reply('Smacznego! :curry:');
    }

    /**
     * @param \Exception $e
     */
    public function closingOrderFailed(\Exception $e)
    {
        $this->reply('Nie udało się zamknąć :(');
    }
}

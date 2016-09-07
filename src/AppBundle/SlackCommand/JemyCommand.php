<?php

namespace AppBundle\SlackCommand;

use Domain\Exception\NotSupportedRestaurantException;
use Domain\Model\Lunch\Order;
use Domain\UseCase\Lunch\InitializeOrder;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class JemyCommand extends AbstractCommand implements InitializeOrder\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $initializeOrderRegex = Regex::match('/jemy (.+)/', $message);

        if ($initializeOrderRegex->hasMatch()) {
            $restaurant = $initializeOrderRegex->group(1);
            $this->initializeOrder($restaurant);

            return true;
        }

        return false;
    }

    protected function initializeOrder(string $restaurant)
    {
        $command = new InitializeOrder\Command($restaurant);

        $useCase = new InitializeOrder();
        $useCase->execute($command, $this);
    }

    public function orderInitializedSuccessfully(Order $order)
    {
        $this->reply('OK, zbieram zamówienia do #'. $order->getRestaurant()->getName());
    }

    public function orderInitializationFailed(\Exception $e)
    {
        if ($e instanceof NotSupportedRestaurantException) {
            return $this->reply('Nie ma takiej restauracji');
        }

        $this->reply('Coś się zapsuło i nie ma zamawiania :(');
    }
}

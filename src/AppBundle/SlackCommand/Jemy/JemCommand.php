<?php

namespace AppBundle\SlackCommand\Jemy;

use AppBundle\SlackCommand\AbstractCommand;
use Domain\Model\Lunch\MenuItem;
use Domain\Model\Lunch\Order;
use Domain\UseCase\Lunch\AddItemToOrder;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class JemCommand extends AbstractCommand implements AddItemToOrder\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $addItemToOrderRegex = Regex::match('/jem (.+)/', $message);

        if ($addItemToOrderRegex->hasMatch()) {
            $this->addItemToOrder();

            return true;
        }

        return false;
    }

    protected function addItemToOrder()
    {
        $command = new AddItemToOrder\Command();

        $useCase = new AddItemToOrder();
        $useCase->execute($command, $this);
    }

    public function successfullyAddedItemToOrder(Order $order, string $userName, MenuItem $addedMenuItem)
    {
        // TODO: Implement successfullyAddedItemToOrder() method.
    }

    public function addingItemToOrderFailed(\Exception $e)
    {
        // TODO: Implement addingItemToOrderFailed() method.
    }
}

<?php

namespace Application\AppBundle\Slack\Command\Lunch;

use Application\AppBundle\Slack\Command\AbstractCommand;
use Domain\UseCase\Lunch\CloseOrder;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\User;

class CloseCommand extends AbstractCommand implements CloseOrder\Responder
{
    public function configure()
    {
        $this->setRegex('/zamknij (\w+)/');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $restaurant = $this->getPart(1);

        $command = new CloseOrder\Command($restaurant);

        $useCase = new CloseOrder(new OrderStorage());
        $useCase->execute($command, $this);
    }

    public function orderClosedSuccessfully(string $restaurantName)
    {
        $this->reply('Smacznego! :curry:');
    }

    public function closingOrderFailed(\Exception $e)
    {
        $this->reply('Nie udało się zamknąć :(');
    }
}

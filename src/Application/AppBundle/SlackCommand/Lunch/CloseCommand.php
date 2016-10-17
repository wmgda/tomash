<?php

namespace Application\AppBundle\SlackCommand\Lunch;

use Application\AppBundle\SlackCommand\TempAbstractCommand;
use Domain\UseCase\Lunch\CloseOrder;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\User;

class CloseCommand extends TempAbstractCommand implements CloseOrder\Responder
{
    public function configure()
    {
        $this->setRegex('/zamknij (\w+)/');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $this->closeOrder($this->getPart(1));

        return true;
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

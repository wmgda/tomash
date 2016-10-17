<?php

namespace Application\AppBundle\Slack\Command\Lunch;

use Application\AppBundle\Slack\Command\AbstractCommand;
use Domain\Model\Lunch\Order;
use Domain\UseCase\Lunch\SumUpOrder;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\Message\Attachment;
use Slack\Message\MessageBuilder;
use Slack\User;

class SumUpCommand extends AbstractCommand implements SumUpOrder\Responder
{
    public function configure()
    {
        $this->setRegex('/podsumuj (.+)/');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $restaurant = $this->getPart(1);

        $command = new SumUpOrder\Command($restaurant);

        $useCase = new SumUpOrder(new OrderStorage());
        $useCase->execute($command, $this);
    }

    public function successfullySummedUpOrder(Order $order, array $items)
    {
        $this->advancedReply(function (MessageBuilder $builder) use ($order, $items) {
            $lines = [];
            $text = 'Lista zamówień w #'. $order->getRestaurant()->getName();
            $builder->setText('<@' . $this->user->getId() . '> ' . $text);

            foreach ($items as $item) {
                $lines[] = sprintf(
                    '%d. %s x%d',
                    $item['item']->getPosition(),
                    $item['item']->getName(),
                    $item['qty']
                );
            }

            $attachment = new Attachment('Zamówienia', implode($lines, "\n"));
            $builder->addAttachment($attachment);

            return $builder;
        });
    }

    public function sumUpOrderFailed(\Exception $e)
    {
        $this->reply('nie udało się wykonać polecenia');
    }
}

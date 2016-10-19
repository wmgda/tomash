<?php

namespace Application\AppBundle\Slack\Command\Lunch;

use Application\AppBundle\Slack\Command\AbstractCommand;
use Domain\Model\Lunch\Order;
use Domain\UseCase\Lunch\CollectBill;
use Infrastructure\File\OrderStorage;
use Slack\Channel;
use Slack\Message\Attachment;
use Slack\Message\MessageBuilder;
use Slack\User;

class VindicateCommand extends AbstractCommand implements CollectBill\Responder
{
    public function configure()
    {
        $this->setRegex('/windykuj (.+)/iu');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $restaurant = $this->getPart(1);

        $command = new CollectBill\Command($restaurant, $this->user->getId());

        $useCase = new CollectBill(new OrderStorage());
        $useCase->execute($command, $this);
    }

    public function billCollectedSuccessfully(Order $order, float $totalSum)
    {
        $this->advancedReply(function (MessageBuilder $builder) use ($order, $totalSum) {
            $lines = [];
            $text = 'Lista zamówień w #'. $order->getRestaurant()->getName();
            $builder->setText('<@' . $this->user->getId() . '> ' . $text);

            foreach ($order->getParticipants() as $participant) {
                $sum = 0;

                foreach ($participant->getItems() as $item) {
                    $sum += $item->getPrice()->toFloat();
                }

                $lines[] = sprintf(
                    '<@%s> %s zł',
                    $participant->getName(),
                    number_format($sum, 2, ',', ' ')
                );
            }

            $attachment = new Attachment('Zamówienia', implode($lines, "\n"));
            $builder->addAttachment($attachment);

            return $builder;
        });
    }

    public function collectingBillFailed(\Exception $e)
    {
        $this->reply('nie udało się wykonać polecenia');
    }
}
<?php

namespace Application\AppBundle\Slack\Command\Lunch;

use Application\AppBundle\Slack\Command\AbstractCommand;
use Application\AppBundle\Slack\Command\CommandInput;
use Application\AppBundle\Slack\Command\CommandOutput;
use Application\AppBundle\Slack\Command\SlackCommand;
use Domain\Model\Lunch\Order;
use Domain\Model\Lunch\Participant;
use Domain\Model\Lunch\SummaryItem;
use Domain\UseCase\Lunch\SumUpOrder;
use Infrastructure\File\OrderStorage;
use Slack\Message\Attachment;

class SumUpCommand extends AbstractCommand implements SlackCommand, SumUpOrder\Responder
{
    /** @var CommandOutput */
    private $output;

    public function configure()
    {
        $this->setRegex('/podsumuj (.+)/');
    }

    public function execute(CommandInput $input, CommandOutput $output)
    {
        $this->output = $output;

        $restaurant = $this->getPart(1);

        $command = new SumUpOrder\Command($restaurant);

        $useCase = new SumUpOrder(new OrderStorage());
        $useCase->execute($command, $this);
    }

    public function successfullySummedUpOrder(Order $order, array $items)
    {
        $this->output->setText('Lista zamówień w #'. $order->getRestaurant()->getName());

        $lines = [];
        /**
         * @var $item SummaryItem
         */
        foreach ($items as $item) {
            $itemPosition = $item->getOrderedMenuItem()->getItem()->getPosition();
            $lines[] = sprintf(
                '%d. %s x%d',
                $itemPosition,
                $item->getOrderedMenuItem()->getItem()->getName(),
                $item->getQuantity()
            );
            /**
             * @var $purchaser Participant
             */
            foreach ($item->getPurchasers() as $purchaser) {
                $annotation = $purchaser->getItemAnnotation($itemPosition);
                if (!empty($annotation)) {
                    $annotation = '(' . $annotation . ')';
                }

                $lines[] = sprintf(
                    '    - %s: %s %s',
                    $purchaser->getName(),
                    (string) $item->getOrderedMenuItem()->getItem()->getPrice(),
                    $annotation
                );
            }
        }

        $attachment = new Attachment('Zamówienia', implode($lines, "\n"));
        $this->output->setAttachment($attachment);
    }

    public function sumUpOrderFailed(\Exception $e)
    {
        $this->output->setText('nie udało się wykonać polecenia');
    }
}

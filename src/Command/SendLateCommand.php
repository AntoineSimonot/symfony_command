<?php


namespace App\Command;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SendLateCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'app:late';

    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;
        parent::__construct();
    }


    // 4. Use the entity manager in the command code ...
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderManager = $this->entityManager->getRepository(Order::class);
        $orders = $orderManager->findAll();
        foreach ($orders as $order) {
            if ( $order->getMaximumDate() < new \DateTimeImmutable() && $order->getState() != "payed" && $order->getState() != "late") {
                $order->setState('late');
                $output->writeln('late');
                $this->entityManager->persist($order);
                $this->entityManager->flush();
            }
        }
        return 0;
    }
}
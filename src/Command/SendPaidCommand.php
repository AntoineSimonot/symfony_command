<?php


namespace App\Command;

use App\Entity\Order;
use App\Helper\EmailHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendPaidCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'app:paid';

    private EntityManagerInterface $entityManager;
    private EmailHelper $emailHelper;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, EmailHelper $emailHelper, MailerInterface $mailer)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;
        $this->emailHelper = $emailHelper;
        $this->mailer = $mailer;
        parent::__construct();
    }


    // 4. Use the entity manager in the command code ...
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderManager = $this->entityManager->getRepository(Order::class);
        $invoicesTotal = $orderManager->findAmountOfPayment();
        foreach ($invoicesTotal as $invoiceTotal) {
            $order = $orderManager->find($invoiceTotal["id"]);
            var_dump($order->getTotalAmount());
            if ($invoiceTotal["amount"] >= $order->getTotalAmount()) {
                $this->emailHelper->SendEmailPayed($this->mailer, $order->getClientEmail(), $order->getId());
                $order->setState('payed');
                $this->entityManager->persist($order);
                $this->entityManager->flush();
            }

        }
        return 0;
    }
}
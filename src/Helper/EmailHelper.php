<?php


namespace App\Helper;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;


class EmailHelper extends AbstractController
{
    public function SendLateEmail($mailer, $clientEmail, $id) {
        $email = (new Email())
            ->from('noreply@order.com')
            ->to($clientEmail)
            ->subject('Concernant votre commande...')
            ->text("Votre payement est en retard pour la commande :" . $id)
            ->html(`<p>Votre payement est en retard pour la commande : $id</p>`);
        $mailer->send($email);
    }

    public function SendInvoice($mailer, $clientEmail, $id, $invoice) {
        $email = (new Email())
            ->from('noreply@order.com')
            ->to($clientEmail)
            ->subject('Concernant votre commande...')
            ->attachFromPath($invoice)
            ->text("Voici votre facture pour la commande :" . $id)
            ->html(`<p>Voici votre facture pour la commande :" . $id</p>`);
        $mailer->send($email);
    }

    public function SendEmailPayed($mailer, $clientEmail, $id) {
        $email = (new Email())
            ->from('noreply@order.com')
            ->to($clientEmail)
            ->subject('Concernant votre commande...')
            ->text("Vous avez entieremment payé la facture pour le commande :" . $id)
            ->html(`<p>Vous avez entieremment payé la facture pour le commande :" . $id</p>`);
        $mailer->send($email);
    }
}
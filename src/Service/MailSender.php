<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailSender
{

    public function SendContactMail($contact, $mailer)
    {
        $email = (new TemplatedEmail())
            ->from('mailer@grandvillars-optique.fr')
            ->replyTo($contact->getEmail())
            ->to('contact@grandvillars-optique.fr')
            ->subject('Message via formulaire - ' . $contact->getSubject() . ' - ' .  $contact->getLastName())
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'contact' => $contact,
            ]);

        $mailer->send($email);
    }

    public function SendRdvMail($contact, $mailer)
    {
        $email = (new TemplatedEmail())
            ->from('mailer@grandvillars-optique.fr')
            ->replyTo($contact->getEmail())
            ->to('contact@grandvillars-optique.fr')
            ->subject('Demande de RDV - ' . $contact->getSubject() . ' - ' .  $contact->getName())
            ->htmlTemplate('emails/rdv.html.twig')
            ->context([
                'contact' => $contact,
            ]);
        
        $mailer->send($email);
    }
}

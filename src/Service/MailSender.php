<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailSender
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function SendContactMail($contact)
    {
        $from = 'mailer@grandvillars-optique.fr';
        $to = 'contact@grandvillars-optique.fr';
        $subject = 'Message via formulaire - ' . $contact->getSubject() . ' - ' .  $contact->getLastName();
        $template = 'emails/contact.html.twig';

        $this->SendMail($from, $to, $subject, $template, $contact, 0);
        $this->SendMail($from, $contact->getEmail(), $subject, $template, $contact, 1);
    }

    public function SendRdvMail($contact)
    {
        $from = 'mailer@grandvillars-optique.fr';
        $to = 'contact@grandvillars-optique.fr';
        $subject = 'Demande de RDV - ' . $contact->getSubject() . ' - ' .  $contact->getName();
        $template = 'emails/rdv.html.twig';

        $this->SendMail($from, $to, $subject, $template, $contact, 0);
        $this->SendMail($from, $contact->getEmail(), $subject, $template, $contact, 1);
    }

    public function  SendMail($from, $to, $subject, $template, $contact, $copy)
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'contact' => $contact,
                'copy' => $copy,
            ]);
        $this->mailer->send($email);
    }



    public function SendResetPasswordMail($to, $token, $lifetime)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('mailer@grandvillars-optique.fr', 'Mailer Grandvillars Optique'))
            ->to($to)
            ->subject('Demande de réinitialisation de mot de passe')
            ->htmlTemplate('emails/resetPassword.html.twig')
            ->context([
                'resetToken' => $token,
                'tokenLifetime' => $lifetime,
            ]);

        $this->mailer->send($email);
    }

}

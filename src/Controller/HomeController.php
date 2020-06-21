<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, MailerInterface $mailer)
    {

        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            dump($contact);

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

            $this->addFlash('success', 'Votre message a bien été evoyé');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

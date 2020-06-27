<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\RdvType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

            $this->addFlash('success', 'Votre message a bien été envoyé');
        }


        $form2 = $this->createForm(RdvType::class);

        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {
            $contact = $form2->getData();

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

            $this->addFlash('success', 'Votre demande a bien été envoyée');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),
        ]);

    }

    /**
     * @Route("/ajaxContact", name="contact")
     */
    public function _ajaxContact(Request $request, MailerInterface $mailer)
    {
        if ($request->isXMLHttpRequest()) {

            $form = $this->createForm(ContactType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $contact = $form->getData();

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

                return new JsonResponse([
                    'status' => 'success',
                ]);
            }

            return $this->render('partials/contact_form.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return new Response('This is not ajax!', 400);
    }
}

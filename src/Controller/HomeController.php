<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\RdvType;
use App\Service\ClosedDays;
use App\Service\MailSender;
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
    public function index(Request $request, ClosedDays $closedDays, MailSender $mailSender)
    {

        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = $contactForm->getData();
            $mailSender->SendContactMail($contact);

            $this->addFlash('success', 'Votre message a bien été envoyé');
        }

        $rdvForm = $this->createForm(RdvType::class);
        $rdvForm->handleRequest($request);

        if ($rdvForm->isSubmitted() && $rdvForm->isValid()) {
            $contact = $rdvForm->getData();
            $mailSender->SendRdvMail($contact);

            $this->addFlash('success', 'Votre demande a bien été envoyée');
            $rdvForm = $this->createForm(RdvType::class);
        }

        return $this->render('home/index.html.twig', [
            'form' => $contactForm->createView(),
            'form2' => $rdvForm->createView(),
            'closeddays' => $closedDays->getHollydays(),
        ]);
    }

    /**
     * @Route("/ajaxContact", name="contact")
     */
    public function _ajaxContact(Request $request, MailSender $mailSender)
    {
        if ($request->isXMLHttpRequest()) {

            $contactForm = $this->createForm(ContactType::class);

            $contactForm->handleRequest($request);

            if ($contactForm->isSubmitted() && $contactForm->isValid()) {
                $contact = $contactForm->getData();

                $mailSender->SendContactMail($contact);

                return new JsonResponse([
                    'status' => 'success',
                ]);
            }

            return $this->render('partials/contact_form.html.twig', [
                'form' => $contactForm->createView(),
            ]);
        }

        return new Response('This is not ajax!', 400);
    }

    /**
     * @Route("/ajaxRdv", name="rdv")
     */
    public function _ajaxRdv(Request $request, ClosedDays $closedDays)
    {
        if ($request->isXMLHttpRequest()) {

            $contactForm = $this->createForm(RdvType::class);

            $contactForm->handleRequest($request);

            if ($contactForm->isValid()) {
                return new JsonResponse([
                    'status' => 'success',
                ]);
            }

            return $this->render('home/modalRdv.html.twig', [
                'form2' => $contactForm->createView(),
                'closeddays' => $closedDays->getHollydays(),
            ]);
        }

        return new Response('This is not ajax!', 400);
    }
}

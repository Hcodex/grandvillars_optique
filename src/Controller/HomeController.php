<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\RdvType;
use App\Repository\ClosingDaysRepository;
use App\Repository\ContentRepository;
use App\Repository\HealthInsuranceRepository;
use App\Repository\MediaRepository;
use App\Repository\TimeTableRepository;
use App\Service\MailSender;
use App\Service\PublicHollydays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil du site
     * 
     * @Route("/", name="homepage")
     */
    public function index(Request $request, MailSender $mailSender, ClosingDaysRepository $closingDayRepo, TimeTableRepository $timeTableRepo, ContentRepository $contentRepo, MediaRepository $mediaRepo)
    {

        $contactForm = $this->manageContactForm($request, $mailSender);

        if ($contactForm === true) {
            $this->addFlash('success', 'Votre message a bien été envoyé');
            $contactForm = $this->createForm(RdvType::class);
        }

        $rdvForm = $this->manageRdvForm($request, $mailSender);

        if ($rdvForm === true) {
            $this->addFlash('success', 'Votre message a bien été envoyé');
            $rdvForm = $this->createForm(RdvType::class);
        }

        return $this->render('home/index.html.twig', [
            'form' => $contactForm->createView(),
            'form2' => $rdvForm->createView(),
            'closingDays'  =>  $closingDayRepo->findAllClosingDays(),
            'publicHollydays' => PublicHollydays::getHollydays(),
            'timeTable' => $timeTableRepo->getFirst(),
            'content' => $contentRepo->getContents(),
            'medias' => $mediaRepo->getMedias(),
            'editorMode' => false,
        ]);
    }


    /**
     * Gère le formulaire de contact
     * 
     */
    public function manageContactForm(Request $request, MailSender $mailSender)
    {

        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = $contactForm->getData();
            $mailSender->SendContactMail($contact);

            return true;
        }

        return $contactForm;
    }

    /**
     * Gère le formulaire de RDV
     * 
     */
    public function manageRdvForm(Request $request, MailSender $mailSender)
    {

        $rdvForm = $this->createForm(RdvType::class);
        $rdvForm->handleRequest($request);

        if ($rdvForm->isSubmitted() && $rdvForm->isValid()) {
            $contact = $rdvForm->getData();
            $mailSender->SendRdvMail($contact);

            return true;
        }

        return $rdvForm;
    }


    /**
     * Traitement ajax du formulaire de contact
     * 
     * @Route("/ajaxContact", name="contact")
     */
    public function _ajaxContact(Request $request, MailSender $mailSender)
    {
        if ($request->isXMLHttpRequest()) {

            $contactForm = $this->manageContactForm($request, $mailSender);

            if ($contactForm === true) {
                return new JsonResponse([
                    'status' => 'success',
                ]);
            } else {
                return $this->render('partials/contact_form.html.twig', [
                    'form' => $contactForm->createView(),
                ]);
            }
        }

        throw new BadRequestHttpException('Requête non Ajax', null, 400);
    }

    /**
     * Traitement ajax du formulaire de rendez-vous
     * 
     * @Route("/ajaxRdv", name="rdv")
     */
    public function _ajaxRdv(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $from = $this->createForm(RdvType::class);

            $from->handleRequest($request);

            $render = $this->renderView('partials/rdv_step1.html.twig', [
                'form2' => $from->createView(),
            ]);

            $status = $from->isValid() ? "success" : "invalid";

            return new JsonResponse([
                'status' => $status,
                'render' => $render,
            ]);
        }

        throw new BadRequestHttpException('Requête non Ajax', null, 400);
    }

    /**
     * Affiche la liste des mutuelles
     * 
     * @Route("/ajaxMutuelles", name="mutuelles")
     */
    public function _ajaxMutuelles(Request $request, HealthInsuranceRepository $healthInsuranceRepo)
    {
        if ($request->isXMLHttpRequest()) {
            return $this->render('partials/mutuelles_list.html.twig', [
                'healthInsurances' => $healthInsuranceRepo->findAll(),
            ]);
        }

        return new Response('This is not ajax!', 400);
    }


    /**
     * Affiche la page politique de confidentialité
     *
     * @Route("/policy", name="policy")
     */
    public function policy()
    {
        return $this->render('legal/policy.html.twig',);
    }

    /**
     * Affiche la page mentions légales
     *
     * @Route("/mentions", name="mentions")
     */
    public function mentions()
    {
        return $this->render('legal/mentions.html.twig',);
    }
}

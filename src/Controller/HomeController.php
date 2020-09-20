<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\RdvType;
use App\Repository\ClosingDaysRepository;
use App\Repository\ContentRepository;
use App\Repository\HealthInsuranceRepository;
use App\Repository\MediaRepository;
use App\Repository\TimeTableRepository;
use App\Service\ClosedDays;
use App\Service\ContentService;
use App\Service\MailSender;
use App\Service\PublicHollydays;
use DateTime;
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
     * Affiche la page d'accueil du site
     * 
     * @Route("/", name="homepage")
     */
    public function index(Request $request, MailSender $mailSender, ClosingDaysRepository $closingDayRepo, TimeTableRepository $timeTableRepo, ContentRepository $contentRepo, MediaRepository $mediaRepo)
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

        $recurrentClosingDays = $closingDayRepo->getRecurentClosingDays();
        foreach ($recurrentClosingDays as $recurrentClosingDay){
            $recurrentClosingDay->forceYear();
        }
        
        return $this->render('home/index.html.twig', [
            'form' => $contactForm->createView(),
            'form2' => $rdvForm->createView(),
            'closingDays'  => array_merge($closingDayRepo->getClosingDays(), $recurrentClosingDays),
            'publicHollydays' => PublicHollydays::getHollydays(),
            'timeTable' => $timeTableRepo->getFirst(),
            'content'=> $contentRepo->getContents(),
            'medias'=>$mediaRepo->getMedias(),
            'editorMode' => false,
        ]);
    }

    /**
     * Gère le formulaire de contact
     * 
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
     * Gère le formulaire de rendez-vous
     * 
     * @Route("/ajaxRdv", name="rdv")
     */
    public function _ajaxRdv(Request $request, ClosingDaysRepository $closingDayRepo)
    {
        if ($request->isXMLHttpRequest()) {

            $contactForm = $this->createForm(RdvType::class);

            $contactForm->handleRequest($request);

            if ($contactForm->isValid()) {
                return new JsonResponse([
                    'status' => 'success',
                ]);
            }

            $recurrentClosingDays = $closingDayRepo->getRecurentClosingDays();
            foreach ($recurrentClosingDays as $recurrentClosingDay){
                $date = $recurrentClosingDay->getStartDate();
                $month = date_format($date, "m");
                $day = date_format($date, "d");
                $newDate = new DateTime();
                $newDate->setDate(date('Y'), $month, $day)
                        ->setTime(0, 0, 0);
                $recurrentClosingDay->setStartDate($newDate);
    
                $date = $recurrentClosingDay->getEndDate();
                $month = date_format($date, "m");
                $day = date_format($date, "d");
                $newDate = new DateTime();
    
                $newDate->setDate(date('Y'), $month, $day)
                        ->setTime(0, 0, 0);
                $recurrentClosingDay->setEndDate($newDate);
            }

            return $this->render('home/modalRdv.html.twig', [
                'form2' => $contactForm->createView(),
                'closingDays'  => array_merge($closingDayRepo->getClosingDays(), $recurrentClosingDays),
                'publicHollydays' => PublicHollydays::getHollydays(),
            ]);
        }

        return new Response('This is not ajax!', 400);
    }

    /**
     * Affiche la liste des mutuelles
     * 
     * @Route("/ajaxMutuelles", name="mutuelles")
     */
    public function _ajaxMutuelles( Request $request, HealthInsuranceRepository $healthInsuranceRepo)
    {
        if ($request->isXMLHttpRequest()) {
               return $this->render('partials/mutuelles_list.html.twig',[
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

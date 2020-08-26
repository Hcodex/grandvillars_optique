<?php

namespace App\Controller;

use DateTime;
use App\Entity\ClosingDays;
use App\Entity\HealthInsurance;
use App\Entity\Media;
use App\Entity\TimeTable;
use App\Form\ClosingDaysType;
use App\Form\TimeTableType;
use App\Form\UploadType;
use App\Service\PublicHollydays;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClosingDaysRepository;
use App\Repository\ContentRepository;
use App\Repository\HealthInsuranceRepository;
use App\Repository\MediaRepository;
use App\Repository\TimeTableRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin_dashboard")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function index(Request $request, EntityManagerInterface $manager, ClosingDaysRepository $closingDayRepo, UserRepository $userRepo, TimeTableRepository $timeTableRepo, HealthInsuranceRepository $healthInsuranceRepo, MediaRepository $mediaRepo)
    {
        $addClosingDay = new ClosingDays;

        $closingDaysForm = $this->createForm(ClosingDaysType::class, $addClosingDay);
        $closingDaysForm->handleRequest($request);

        $media = new Media;

        $uploadForm = $this->createForm(UploadType::class, $media);
        $uploadForm->handleRequest($request);

        if ($closingDaysForm->isSubmitted() && $closingDaysForm->isValid()) {
            $manager->persist($addClosingDay);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le jour de fermeture a été ajouté"
            );
        }

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
            $manager->persist($media);
            $manager->flush();

            $this->addFlash(
                'success',
                "Image ajoutée"
            );
        }

        $recurrentClosingDays = $closingDayRepo->getRecurentClosingDays();
        foreach ($recurrentClosingDays as $recurrentClosingDay) {
            $recurrentClosingDay->forceYear();
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'form' =>  $closingDaysForm->createView(),
            'closingDays'  => array_merge($closingDayRepo->getClosingDays(), $recurrentClosingDays),
            'time' => date("Y-m-d H:i:s"),
            'publicHollydays' => PublicHollydays::getHollydays(),
            'users' => $userRepo->findAll(),
            'timeTable' => $timeTableRepo->getFirst(),
            'healthInsurances' => $healthInsuranceRepo->findAll(),
            'uploadForm' =>  $uploadForm->createView(),
            'medias' => $mediaRepo->findAll(),
        ]);
    }

    /**
     * Delete a closing day
     * 
     * @Route("/admin/closingday/{id}/delete", name="admin_closingday_delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     *
     * @param ClosingDays $closingDays
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteClosingDay(ClosingDays $closingDays, EntityManagerInterface $manager)
    {
        $manager->remove($closingDays);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le jour de fermeture a été supprimé"
        );

        return $this->redirectToROute('admin_dashboard');
    }

    /**
     * @Route("admin/calendar/{targetDate}", name="calendar")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function _ajaxCalendarNextMonth($targetDate, ClosingDaysRepository $closingDayRepo)
    {
        $year = date("Y", $targetDate);
        $recurrentClosingDays = $closingDayRepo->getRecurentClosingDays();
        foreach ($recurrentClosingDays as $recurrentClosingDay) {
            $recurrentClosingDay->forceYear($year);
        }
        return $this->render('admin/partials/modalCalendar.html.twig', [
            'closingDays'  => array_merge($closingDayRepo->getClosingDays(), $recurrentClosingDays),
            'time' => date("Y-m-d", $targetDate),
            'publicHollydays' => PublicHollydays::getHollydays(),
        ]);
    }



    /**
     * Permet d'éditer le tableau des horaires
     * 
     * @Route("/admin/timeTable/{id}/edit", name="admin_timeTable_edit")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     * 
     * @return Response
     */
    public function editTimeTable(TimeTable $editedTimeTable, Request $request,  EntityManagerInterface $manager)
    {
        $form = $this->createForm(TimeTableType::class, $editedTimeTable);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($editedTimeTable);
            $manager->flush();

            $this->addFlash(
                'success',
                "Horaires modifiées"
            );

            return $this->redirectToRoute("admin_dashboard");
        }

        return $this->render('admin/editTimeTable.html.twig', [
            'form' => $form->createView(),
            'timeTable' => $editedTimeTable,
        ]);
    }



    /**
     * Edit le staut dun mutuelle
     * 
     * @Route("admin/healthInsurance/{id}/{status}", name="admin_healthInsurance_Status_Edit")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function _ajaxHealthInsuranceStatusEdit(HealthInsurance $healthInsurance, $status, Request $request, EntityManagerInterface $manager)
    {

        if ($request->isXMLHttpRequest()) {
            switch ($status) {
                case "hide":
                    $healthInsurance->setStatus(0);
                    break;
                case "enable":
                    $healthInsurance->setStatus(1);
                    break;
                case "disable":
                    $healthInsurance->setStatus(2);
                    break;
                default:
                    return new Response('Erreur', 400);
            }
            $manager->persist($healthInsurance);
            $manager->flush();
            return new Response('Ok');
        }

        return new Response('This is not ajax!', 400);
    }






    /**
     * @Route("/admin/upload", name="ajax_upload")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function _ajaxUpload(Request $request, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {
            $media = new Media;

            $uploadForm = $this->createForm(UploadType::class, $media);
            $uploadForm->handleRequest($request);

            if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                $manager->persist($media);
                $manager->flush();

                return new Response('Ok');
            }
        }

        return new Response('This is not ajax!', 400);
    }
}

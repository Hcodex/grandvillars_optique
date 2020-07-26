<?php

namespace App\Controller;

use DateTime;
use App\Entity\ClosingDays;
use App\Entity\TimeTable;
use App\Form\ClosingDaysType;
use App\Form\TimeTableType;
use App\Service\PublicHollydays;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClosingDaysRepository;
use App\Repository\ContentRepository;
use App\Repository\TimeTableRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin_dashboard")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function index(Request $request, EntityManagerInterface $manager, ClosingDaysRepository $closingDayRepo, UserRepository $userRepo, TimeTableRepository $timeTableRepo)
    {
        $addClosingDay = new ClosingDays;

        $closingDaysForm = $this->createForm(ClosingDaysType::class, $addClosingDay);
        $closingDaysForm->handleRequest($request);

        if ($closingDaysForm->isSubmitted() && $closingDaysForm->isValid()) {
            $manager->persist($addClosingDay);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le jour de fermeture a été ajouté"
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
            'timeTable' => $timeTableRepo->find(1),
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
}

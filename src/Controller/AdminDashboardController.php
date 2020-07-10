<?php

namespace App\Controller;

use App\Entity\ClosingDays;
use App\Form\ClosingDaysType;
use App\Repository\ClosingDaysRepository;
use App\Service\PublicHollydays;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin_dashboard")
     */
    public function index(Request $request, EntityManagerInterface $manager, ClosingDaysRepository $closingDayRepo)
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
        foreach ($recurrentClosingDays as $recurrentClosingDay){
            $recurrentClosingDay->forceYear();
        }
        
        return $this->render('admin/dashboard/index.html.twig', [
            'form' =>  $closingDaysForm->createView(),
            'closingDays'  => array_merge($closingDayRepo->getClosingDays(), $recurrentClosingDays),
            'time' => date("Y-m-d H:i:s"),
            'publicHollydays' => PublicHollydays::getHollydays(),

        ]);
    }

    /**
     * Delete a closing day
     * 
     * @Route("/admin/closingday/{id}/delete", name="admin_closingday_delete")
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
     */
    public function _ajaxCalendarNextMonth($targetDate, ClosingDaysRepository $closingDayRepo)
    {
        $year = date("Y", $targetDate);
        $recurrentClosingDays = $closingDayRepo->getRecurentClosingDays();
        foreach ($recurrentClosingDays as $recurrentClosingDay){
            $recurrentClosingDay->forceYear($year);
        }
        return $this->render('admin/partials/modalCalendar.html.twig', [
            'closingDays'  => array_merge($closingDayRepo->getClosingDays(), $recurrentClosingDays),
            'time' => date("Y-m-d", $targetDate),
            'publicHollydays' => PublicHollydays::getHollydays(),
        ]);
    }
}

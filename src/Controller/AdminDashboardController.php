<?php

namespace App\Controller;

use App\Entity\ClosingDays;
use App\Form\ClosingDaysType;
use App\Repository\ClosingDaysRepository;
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
        $closingDay = new ClosingDays;

        $closingDaysForm = $this->createForm(ClosingDaysType::class, $closingDay);
        $closingDaysForm->handleRequest($request);

        if ($closingDaysForm->isSubmitted() && $closingDaysForm->isValid()) {
            $manager->persist($closingDay);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été crée !"
            );
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'form' =>  $closingDaysForm->createView(),
            'closingDays'  => $closingDayRepo->getClosingDays(),
        ]);
    }
}

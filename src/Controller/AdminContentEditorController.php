<?php

namespace App\Controller;

use App\Form\ContentType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminContentEditorController extends AbstractController
{
    /**
     * @Route("/admin/contentEditor", name="admin_content_editor")
     */
    public function index(ContentRepository $contentRepo, Request $request, EntityManagerInterface $manager)
    {
        $quoteContent = $contentRepo->find(28);
        $quoteForm = $this->createForm(ContentType::class, $quoteContent);
        $quoteForm->handleRequest($request);

        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $manager->persist($quoteContent);
            $manager->flush();

            $this->addFlash(
                'success',
                "Contenu mis à jour"
            );
        }

        return $this->render('admin/content_editor/index.html.twig', [
            'quoteForm' => $quoteForm->createView(),
            'quoteContent' => $quoteContent,
            'controller_name' => 'AdminContentEditorController',
        ]);
    }
}

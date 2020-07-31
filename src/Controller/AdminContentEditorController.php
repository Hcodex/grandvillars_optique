<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContentEditorController extends AbstractController
{
    /**
     * @Route("/admin/contentEditor", name="admin_content_editor")
     */
    public function index(ContentRepository $contentRepo)
    {
        $quoteContent = $contentRepo->findOneByCategoryField('quoteSection');
        $quoteForm = $this->createForm(ContentType::class, $quoteContent);

        $serviceContent = $contentRepo->findOneByCategoryField('serviceSection');
        $serviceForm = $this->createForm(ContentType::class, $serviceContent);

        return $this->render('admin/content_editor/index.html.twig', [
            'quoteForm' => $quoteForm->createView(),
            'quoteContent' => $quoteContent,
            'serviceForm' => $serviceForm->createView(),
            'serviceContent' => $serviceContent,
            'controller_name' => 'AdminContentEditorController',
        ]);
    }


    /**
     * @Route("/admin/content/{id}/update", name="admin_content_update")
     */
    public function updateContent(Content $content, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() &&  $form->isValid()) {
            $manager->persist($content);
            $manager->flush();

            $this->addFlash(
                'success',
                "Contenu mis à jour"
            );
        } else {
            $this->addFlash(
                'danger',
                "Erreur lors de la mise à jour"
            );
        }

        return $this->redirectToRoute('admin_content_editor');
    }

    /**
     * @Route("/admin/content/{id}/axjaxUpdate", name="admin_content_ajaxUpdate")
     */
    public function _ajaxUpdateContent(Content $content, Request $request, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {

            $form = $this->createForm(ContentType::class, $content);
            $form->handleRequest($request);

            if ($form->isSubmitted() &&  $form->isValid()) {
                $manager->persist($content);
                $manager->flush();

                switch ($content->getContentCategory()->getName()) {
                    case "quoteSection":
                        return $this->render('admin/content_editor/quoteSection.html.twig', [
                            'quoteForm' => $form->createView(),
                            'quoteContent' => $content,
                        ]);
                        break;
                    case "serviceSection":
                        return $this->render('admin/content_editor/serviceSection.html.twig', [
                            'serviceForm' => $form->createView(),
                            'serviceContent' => $content,
                        ]);
                        break;
                }
            }
        }

        return new Response('This is not ajax!', 400);
    }
}

<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentCategoryRepository;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminContentEditorController extends AbstractController
{
    /**
     * @Route("/admin/contentEditor", name="admin_content_editor")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ContentRepository $contentRepo, ContentCategoryRepository $contentCategoryRepo)
    {

        $categories = $contentCategoryRepo->findAll();

        foreach ($categories as $categorie) {
            $items = $contentRepo->findByCategoryField($categorie->getName());

            foreach ($items as $item) {
                $itemForm = $this->createForm(ContentType::class, $item)->createView();
                $arg[$categorie->getName()][$item->getId()] = [
                    'form' => $itemForm,
                    'entity' => $item
                ];
            }
        }
        dump($arg);

        return $this->render('admin/content_editor/index.html.twig', $arg);
    }


    /**
     * @Route("/admin/content/{id}/update", name="admin_content_update")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function _ajaxUpdateContent(Content $content, Request $request, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {

            $form = $this->createForm(ContentType::class, $content);
            $form->handleRequest($request);

            if ($form->isSubmitted() &&  $form->isValid()) {
                $manager->persist($content);
                $manager->flush();

                $categorie = $content->getContentCategory()->getName();

                $arg['item'] = [
                    'form' => $form->createView(),
                    'entity' => $content
                ];

                return $this->render('admin/content_editor/' . $categorie . '.html.twig', $arg);
            }
        }

        return new Response('This is not ajax!', 400);
    }
}

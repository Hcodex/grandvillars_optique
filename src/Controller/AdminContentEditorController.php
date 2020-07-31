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
        $items = ["quoteSection", "serviceSection", "activisuSection"];

        $arg = array();
        foreach ($items as $item) {
            ${$item . 'Content'} = $contentRepo->findOneByCategoryField($item);
            ${$item . 'Form'} = $this->createForm(ContentType::class, ${$item . 'Content'});
            $arg = array_merge($arg, array($item . 'Form' => ${$item . 'Form'}->createView()));
            $arg = array_merge($arg, array($item . 'Content' => ${$item . 'Content'}));
        }

        return $this->render('admin/content_editor/index.html.twig', $arg );
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
                    case "activisuSection":
                        return $this->render('admin/content_editor/activisuSection.html.twig', [
                            'activisuForm' => $form->createView(),
                            'activisuContent' => $content,
                        ]);
                        break;
                }
            }
        }

        return new Response('This is not ajax!', 400);
    }
}

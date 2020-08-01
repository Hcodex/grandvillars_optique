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
        $sections = ["quoteSection", "serviceSection", "activisuSection"];
        $itemCategories = ["jobItem", "serviceItem"];

        $arg = array();
        foreach ($sections as $section) {
            ${$section . 'Content'} = $contentRepo->findOneByCategoryField($section);
            ${$section . 'Form'} = $this->createForm(ContentType::class, ${$section . 'Content'});
            $arg = array_merge($arg, array($section . 'Form' => ${$section . 'Form'}->createView()));
            $arg = array_merge($arg, array($section . 'Content' => ${$section . 'Content'}));
        }

        foreach ($itemCategories as $itemCategorie) {
            $items = $contentRepo->findByCategoryField($itemCategorie);
            $i = 1;
            foreach ($items as $item) {
                $arg2 = array();
                ${$itemCategorie . $i . 'Form'} = $this->createForm(ContentType::class, $item);
                $arg2 = array_merge($arg2, array($itemCategorie . 'Form' => ${$itemCategorie . $i . 'Form'}->createView()));
                $arg2 = array_merge($arg2, array($itemCategorie . 'Content' => $item));
                $arg[$itemCategorie][$i] =  $arg2;
                $i++;
            }
        }

        return $this->render('admin/content_editor/index.html.twig', $arg);
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
                            'quoteSectionForm' => $form->createView(),
                            'quoteSectionContent' => $content,
                        ]);
                        break;
                    case "serviceSection":
                        return $this->render('admin/content_editor/serviceSection.html.twig', [
                            'serviceSectionForm' => $form->createView(),
                            'serviceSectionContent' => $content,
                        ]);
                        break;
                    case "activisuSection":
                        return $this->render('admin/content_editor/activisuSection.html.twig', [
                            'activisuSectionForm' => $form->createView(),
                            'activisuSectionContent' => $content,
                        ]);
                        break;
                    case "jobItem":
                        return $this->render('admin/content_editor/jobSection.html.twig', [
                            'item' => array(
                                'jobItemForm' => $form->createView(),
                                'jobItemContent' => $content,
                            )
                        ]);
                        break;
                    case "serviceItem":
                        return $this->render('admin/content_editor/serviceItem.html.twig', [
                            'service' => array(
                                'serviceItemForm' => $form->createView(),
                                'serviceItemContent' => $content,
                            )
                        ]);
                        break;
                }
            }
        }

        return new Response('This is not ajax!', 400);
    }
}

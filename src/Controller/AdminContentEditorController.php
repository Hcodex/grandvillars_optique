<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentIconType;
use App\Form\ContentType;
use App\Repository\ContentCategoryRepository;
use App\Repository\ContentRepository;
use App\Repository\MediaCategoryRepository;
use App\Repository\MediaRepository;
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
    public function index(ContentRepository $contentRepo, ContentCategoryRepository $contentCategoryRepo, MediaRepository $mediaRepo)
    {
         return $this->render('home/index.html.twig', [
            'content'=> $contentRepo->getContents(),
            'medias'=>$mediaRepo->getMedias(),
            'iconList'=>$this->getParameter('iconList'),
            'editorMode' => true,
        ]);
    }

    /**
     * @Route("/admin/content/{content}/axjaxContentFormCreate", name="admin_content_ajaxFormCreate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function _ajaxContentFormCreate(Content $content, Request $request, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {

            $categoryName = $content->getContentCategory()->getName();

            $form = $this->createForm(ContentType::class, $content,[
                "category" => $categoryName,
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() &&  $form->isValid()) {
                $manager->persist($content);
                $manager->flush();

                $arg['item'] = $content;
                $arg['editorMode'] = true;

                $render = $this->renderView('home/' . $categoryName. '.html.twig', $arg);

                return new JsonResponse([
                    'status' => 'success',
                    'render' => $render,
                ]);

            }

            return $this->render('admin/content_editor/modalContentForm.html.twig', [
                'form' =>   $form->createView(),
                'entity' => $content,
            ]);

        }

        return new Response('This is not ajax!', 400);
    }
}

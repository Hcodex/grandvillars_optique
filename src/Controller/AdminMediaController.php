<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\MediaCategory;
use App\Form\DefineMediaType;
use App\Form\MediaType;
use App\Repository\MediaCategoryRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AdminMediaController extends AbstractController
{

    /**
     * @Route("/admin/upload/{type}", name="ajax_upload")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function _ajaxUpload(String $type, Request $request, EntityManagerInterface $manager, MediaCategoryRepository $mediaCategoryRepo)
    {
        if ($request->isXMLHttpRequest()) {

            $media = new Media;

            if ($type != "default") {
                $media->addMediaCategory($mediaCategoryRepo->findByName($type));
            }

            $uploadForm = $this->createForm(MediaType::class, $media,  [
                "action" => $this->generateUrl('ajax_upload', ['type' => $type]),
            ]);

            $uploadForm->handleRequest($request);

            if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                $manager->persist($media);
                $manager->flush();

                $render = $this->renderView('admin/partials/mediaRow.html.twig', [
                    'media' => $media,
                ]);

                return new JsonResponse([
                    'status' => 'success',
                    'render' => $render,
                ]);
            }

            return $this->render('admin/partials/modalUploadForm.html.twig', [
                'uploadForm' => $uploadForm->createView(),
            ]);
        }

        throw new BadRequestHttpException('Requête non Ajax', null, 400);
    }

    /**
     * Supprime un média
     * 
     * @Route("/admin/media/{id}/delete", name="admin_media_delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     *
     * @param Media $media
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function _ajaxDeleteMedia(Request $request, Media $media, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {
            $mediaId = $media->getId();

            $manager->remove($media);
            $manager->flush();

            return new Response($mediaId);
        }

        throw new BadRequestHttpException('Requête non Ajax', null, 400);
    }

    /**
     * Permet d'éditer un média
     * 
     * @Route("/admin/media/{id}/edit", name="admin_media_edit")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     * 
     * @param Media $media
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editMedia(Media $media, Request $request,  EntityManagerInterface $manager, MediaCategoryRepository $mediaCategoryRepo)
    {

        $form = $this->createForm(MediaType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($media);
            $manager->flush();

            $this->addFlash(
                'success',
                "Média modifié avec succès"
            );

            return $this->redirectToRoute("admin_dashboard", ['_fragment' => 'imagesAdmin']);
        }

        return $this->render('admin/dashboard/editMedia.html.twig', [
            'form' => $form->createView(),
            'media' => $media,
        ]);
    }

    /**
     * Permet de lier une catégorie spéciale à un média
     * 
     * @Route("/admin/media/{name}/define", name="admin_media_define")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     * 
     */
    public function defineMedia(MediaCategory $mediaCategory, Request $request, EntityManagerInterface $manager, MediaRepository $mediaRepo)
    {

        $form = $this->createForm(DefineMediaType::class, $mediaCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($mediaCategory);
            $manager->flush();

            $this->addFlash(
                'success',
                "Média modifié avec succès"
            );

            return $this->redirectToRoute("admin_dashboard", ['_fragment' => 'imagesAdmin']);
        }

        return $this->render('admin/dashboard/DefineMedia.html.twig', [
            'form' => $form->createView(),
            'mediaCategory' => $mediaCategory,
            'medias' => $mediaRepo->findAll()
        ]);
    }

    /**
     * Permet de lier une catégorie spéciale à un média
     * 
     * @Route("/admin/media/{name}/axjaxMediaSelectorCreate", name="admin_media_selector_create")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     * 
     */
    public function _ajaxMediaSelectorCreate(MediaCategory $mediaCategory, EntityManagerInterface $manager,  Request $request,  MediaRepository $mediaRepo)
    {
        if ($request->isXMLHttpRequest()) {

            $form = $this->createForm(DefineMediaType::class, $mediaCategory);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($mediaCategory);
                $manager->flush();

                return new Response('OK');
            }

            return $this->render('admin/content_editor/modalMediaSelector.html.twig', [
                'form' =>   $form->createView(),
                'medias' => $mediaRepo->findAll(),
                'mediaCategory' => $mediaCategory,
            ]);
        }

        throw new BadRequestHttpException('Requête non Ajax', null, 400);
    }
}

<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\MediaCategory;
use App\Form\DefineMediaType;
use App\Form\EditMediaType;
use App\Form\UploadType;
use App\Repository\MediaCategoryRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminMediaController extends AbstractController
{

    /**
     * @Route("/admin/upload", name="ajax_upload")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_STAFF')")
     */
    public function _ajaxUpload(Request $request, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {
            $media = new Media;



            $uploadForm = $this->createForm(UploadType::class, $media);
            $uploadForm->handleRequest($request);

            if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                $manager->persist($media);
                $manager->flush();

                return $this->render('admin/partials/mediaRow.html.twig', [
                    'media' => $media,
                ]);
            }
        }

        return new Response('This is not ajax!', 400);
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
    public function _ajaxdeleteMedia(Request $request, Media $media, EntityManagerInterface $manager)
    {
        if ($request->isXMLHttpRequest()) {
            $mediaId = $media->getId();

            $manager->remove($media);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'image'a été supprimée"
            );

            return new Response($mediaId);
        }
        return new Response('This is not ajax!', 400);
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
    public function editMedia(Media $media, Request $request,  EntityManagerInterface $manager)
    {

        $form = $this->createForm(EditMediaType::class, $media);

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
     * Permet d'éditer un média
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
            dump($mediaCategory);

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
}

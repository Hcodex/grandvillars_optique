<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\MediaCategory;
use App\Form\AddMutuelleType;
use App\Form\DefineMediaType;
use App\Form\EditMediaType;
use App\Form\MediaType;
use App\Form\UploadType;
use App\Repository\MediaCategoryRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Else_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

            if ($type == "mutuelle"){
                $uploadForm = $this->createForm(MediaType::class, $media,  [
                    "mutuelle"=> true,
                    "action"=> $this->generateUrl('ajax_upload', ['type' => "mutuelle"]),
                ]);
                $media->addMediaCategory($mediaCategoryRepo->findByName("mutuelle"));
            }
            else{
                $uploadForm = $this->createForm(MediaType::class, $media, [
                    "mutuelle"=> false,
                    "action"=> $this->generateUrl('ajax_upload', ['type' => "default" ]),
                ]);
            }

            $uploadForm->handleRequest($request);

            if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                $manager->persist($media);
                $manager->flush();

                return $this->render('admin/partials/mediaRow.html.twig', [
                        'media' => $media,
                    ]);

            }

            return $this->render('admin/partials/modalUploadForm.html.twig', [
                'uploadForm' => $uploadForm->createView(),
            ]);

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
    public function editMedia(Media $media, Request $request,  EntityManagerInterface $manager, MediaCategoryRepository $mediaCategoryRepo)
    {

        $lockedCategories = $this->getParameter('media.lockedCategories');
        $mediaLockedCategories = array_intersect($media->getCategories(), $lockedCategories);

        if (in_array("mutuelle",$media->getCategories() )){
            $form = $this->createForm(MediaType::class, $media,  [
                "mutuelle"=> true,
            ]);
            $media->addMediaCategory($mediaCategoryRepo->findByName("mutuelle"));
        }
        else{
            $form = $this->createForm(MediaType::class, $media, [
                "mutuelle"=> false,
            ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($mediaLockedCategories as $categorie) {
                $media->addMediaCategory($mediaCategoryRepo->findByName($categorie));
            }

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

        return new Response('This is not ajax!', 400);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Connexion utilisateur 
     * 
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Déconnexion
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout()
    {
    }


    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/admin/password-update", name="account_password")
     * @IsGranted("ROLE_USER", message="Vous ne pouvez pas accéder à cette page")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                $form->get('oldPassword')->addError(new FormError('Le mot de passe que vous avez saisi n\'est pas votre mot de passe actuel'));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute("admin_dashboard");
            }
        }
        return $this->render('admin/account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

        /**
     * Permet de créer un utilisateur
     * 
     * @Route("/admin/register", name="account_register")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function register(Request $request,  EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Utilisateur créé avec succès !"
            );

            return $this->redirectToRoute("admin_dashboard");
        }

        return $this->render('admin/account/registration.html.twig', [
            'form' => $form->createView()
        ]);

    }

        /**
     * Permet d'éditer un utilisateur
     * 
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * @Security("is_granted('ROLE_ADMIN') and user.getId() != editedUser.getId()", message="Vous ne pouvez pas modifier votre propre profil")

     * 
     * @return Response
     */
    public function editUser(User $editedUser, Request $request,  EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $form = $this->createForm(EditUserType::class, $editedUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($editedUser);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Utilisateur modifié avec succès"
            );

            return $this->redirectToRoute("admin_dashboard");
        }

        return $this->render('admin/account/editUser.html.twig', [
            'form' => $form->createView(),
            'user' => $editedUser,
        ]);
    }

     /**
     * Delete user
     * 
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * @Security("is_granted('ROLE_ADMIN') and user.getId() != deletedUser.getId()", message="Vous ne pouvez pas supprimer votre propre profil")
     *
     * @return Response
     */
    public function deleteClosingDay(User $deletedUser, EntityManagerInterface $manager)
    {
        $manager->remove($deletedUser);
        $manager->flush();

        $this->addFlash(
            'success',
            "Utilisateur supprimé avec succès"
        );

        return $this->redirectToRoute('admin_dashboard');
    }
}

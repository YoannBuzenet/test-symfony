<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Display and handle login form
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     * 
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'has_error' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Allows logout
     * 
     * @route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){
        // Nada
    }

    /**
     * Allow user to register and display form
     *
     * @return Response
     * 
     * @Route("/register", name="account_register")
     * 
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.'
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Displays and handle editing profile form
     *
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){

        $user=$this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre profil a bien été édité !'
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Edit password
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){

        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Checking old password match current password
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError('Le mot de passe saisi n\'est pas valide'));
            }
            else {
                //Saving password
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié.');
                
                    return $this->redirectToRoute('account_password');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
     * Displays user's profile
     *
     * @return Response
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function myAccount(){

        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }
}

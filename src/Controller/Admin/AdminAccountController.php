<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();


        return $this->render('admin/account/login.html.twig', [
            'has_error' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Allows logout
     * 
     * @route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout(){
        // Nada
    }
}

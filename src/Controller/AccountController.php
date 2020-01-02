<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function login()
    {
        return $this->render('account/login.html.twig');
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
}

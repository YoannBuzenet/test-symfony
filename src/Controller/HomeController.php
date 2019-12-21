<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * Say Hello with first name
     * 
     * @Route("/hello/{prenom}", name = "hello")
     *
     * @return void
     */
    public function hello($prenom = 'Rien'){
        return $this->render('hello.html.twig', [
            'prenom' => $prenom
        ]);
    }


        /**
         * @Route("/", name="homepage")
         */
    public function home(){
        
        $prenoms = ['yoann' => 32, 'eric' => 33, 'chamourix'=>34];

        return $this->render(
            'home.html.twig', [
                'title' => 'Bonjour à Tous !',
                'age' => 19,
                'tableau' => $prenoms
                ]
        );
    }
}


?>
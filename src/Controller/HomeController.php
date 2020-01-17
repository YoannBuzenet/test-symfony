<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
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
    public function home(AdRepository $adRepo, UserRepository $userRepo){

        return $this->render(
            'home.html.twig', [
                'ads'=>$adRepo->findBestAds(3),
                'users'=> $userRepo->findBestUsers(2)
            ]
        );
    }
}


?>
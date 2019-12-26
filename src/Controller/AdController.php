<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Ad Creation
     * 
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */
    public function create(){

        $ad = new Ad();

        $form = $this->createFormBuilder($ad)
                     ->add('title')
                     ->add('introduction')
                     ->add('content')
                     ->add('rooms')
                     ->add('price')
                     ->add('coverImage')
                     ->getForm();


        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Display one ad with details
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad){
        //Grace au Param Converter, l'Ad est récupérée en tache de fond avec le Repository et le slug passé en paramètre
        //$ad = $repo->findOneBySlug($slug);

        return $this->render('/ad/show.html.twig', [
            'ad'=> $ad
        ]);
    }
}

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
     * Display one ad with details
     *
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show($slug, AdRepository $repo){
        $ad = $repo->findOneBySlug($slug);

        return $this->render('/ad/show.html.twig', [
            'ad'=> $ad
        ]);
    }
}

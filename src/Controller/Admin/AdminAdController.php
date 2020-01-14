<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }


    /**
     * Display edit form on $ad
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "The ad {$ad->getTitle()} has been edited successfully."
            );
        }

        return $this->render('admin/ad/edit.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }
}

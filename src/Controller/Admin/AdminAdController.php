<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PaginationService;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page":"\d+"})
     */
    public function index(AdRepository $repo, $page = 1, PaginationService $paginator)
    {
        $paginator->setEntityClass(Ad::class)
                  ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $paginator
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

    /**
     * Deletes an ad
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $a
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager){
        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "The ad <strong>{$ad->getTitle()}</strong> can not be deleted, as it has already somes bookings made on it."
            );
        }
        else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "The ad <strong>{$ad->getTitle()}</strong> has been deleted."
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}

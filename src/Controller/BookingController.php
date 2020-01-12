<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form= $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user= $this->getUser();

            $booking->setBooker($user)
                    ->setAd($ad);
            
            //If chosen dates are not available, error message ; else ; persist & flush
            if(!$booking->isBookableDates()){
                $this->addFlash(
                    'warning',
                    'Les dates sélectionnées ne sont pas disponibles. Merci d\'en choisir d\'autres.'
                );
            }
            else {
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
            ]);
            }
            
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Display a booking presentation page
     * 
     * @Route("/booking/{id}", name="booking_show")
     *
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function show(Booking $booking, Request $request, EntityManagerInterface $manager){

        $comment = new Comment();

        $form = $this->createform(CommentType::class, $comment,);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());
            
            $manager->persist($comment);
            $manager->flush();

            $this->addflash(
                'success',
                'Your comment has been well posted.'
            );
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        $bookings = $repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Edit a booking with admin
     * 
     *@Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * 
     * @return Response
     */
    public function edit(Booking $booking){

        $form = $this->createForm(AdminBookingType::class, $booking);

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }
}

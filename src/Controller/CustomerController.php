<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/new", name="new_reservation_panel")
     */
    public function newReservationPanele(): Response
    {
        return $this->render('customer/index.html.twig');
    }

    /**
     * @Route("/new/{slug}", name="reservation_panel")
     */
    public function reservationPanel($slug): Response
    {
        return $this->render('customer/index.html.twig');
    }
}

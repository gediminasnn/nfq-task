<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationController extends AbstractController
{

    private $customerRepository;
    private $reservationRepository;

    /**
     * ReservationController constructor.
     */
    public function __construct(ReservationRepository $reservationRepository,CustomerRepository $customerRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->customerRepository = $customerRepository;
    }

    //  TODO : make working newReservationPanel method
    /**
     * @Route("/reservations/new", name="new_reservation_panel")
     */
    public function newReservationPanel(UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->isGranted('ROLE_SPECIALIST')){
            return new RedirectResponse($urlGenerator->generate('specialist'));
        }
        return $this->render();
    }

    /**
     * @Route("/reservations/{reservationCode}", name="reservations_panel")
     */
    public function reservationPanel($reservationCode): Response
    {
        $reservation = $this->reservationRepository->findOneBy(['code' => $reservationCode]);

        return $this->render('reservation/reservation.html.twig', [
            'reservation' => $reservation
        ]);
    }
}

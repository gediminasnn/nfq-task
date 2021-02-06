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

    private $reservationRepository;

    /**
     * ReservationController constructor.
     * @param ReservationRepository $reservationRepository
     */
    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;

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
     * @Route("/reservations/{reservationCode}", name="reservation_panel")
     */
    public function reservationPanel($reservationCode): Response
    {
        $reservation = $this->reservationRepository->findOneBy(['code' => $reservationCode]);

        return $this->render('reservation/reservation.html.twig', [
            'reservation' => $reservation
        ]);
    }

    /**
     * @Route("/reservations/delete/{reservationCode}", name="delete_reservation")
     */
    public function deleteReservation($reservationCode): Response
    {
        $reservation = $this->reservationRepository->findOneBy(['code' => $reservationCode]);
        $this->reservationRepository->removeReservation($reservation);

        //TODO : throw success message 5/10
        return $this->render('home/index.html.twig');
    }



}

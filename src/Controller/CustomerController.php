<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public function newVisitButtonForCustomer(UrlGeneratorInterface $urlGenerator): Response
    {
        if ($this->isGranted('ROLE_SPECIALIST')) {
            return new RedirectResponse($urlGenerator->generate('customer_management'));
        }
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/reservations/{reservationCode}", name="reservation_panel")
     * @param $reservationCode
     * @return Response
     */
    public function reservationPanel($reservationCode, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findOneBy(['code' => $reservationCode]);
        if (!$reservation) {
            throw new NotFoundHttpException("The reservation (code :{$reservationCode}) doesn't exist");
        }

        $reservationQueuePosition = $reservationRepository->findReservationQueuePosition($reservation);
        $timeLeft = $reservation->getStartTime()->diff(new \DateTime('now'));

        return $this->render('reservation/reservation.html.twig', [
            'reservation' => $reservation,
            'reservationQueuePosition' => $reservationQueuePosition,
            'timeLeft' => $timeLeft

        ]);
    }

}
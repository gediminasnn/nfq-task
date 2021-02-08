<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SpecialistController extends AbstractController
{
    /**
     * @Route("/customers/management/", name="customer_management")
     */
    public function customerManagementPanel(Request $request, UrlGeneratorInterface $urlGenerator, SpecialistRepository $specialistRepository, ReservationRepository $reservationRepository): Response
    {
        if ($this->isGranted('IS_ANONYMOUS')) {
            return new RedirectResponse($urlGenerator->generate('home'));
        }

        $specialist = $specialistRepository->findOneBy(['email' => $this->getUser()->getUsername()]);
        $reservations = $reservationRepository->getAllUpcomingPendingReservationsBySpecialist($specialist);
        $begunReservations = $reservationRepository->getAllBegunReservationBySpecialist($specialist);
        $alertMessage = $request->query->get('alertMessage');

        return $this->render('specialist/customermanagement.html.twig', [
            'specialist' => $specialist,
            'reservations' => $reservations,
            'begunReservations' => $begunReservations,
            'alertMessage' => $alertMessage
        ]);
    }

    /**
     * @Route("/customers/management/inverse", name="customer_management_inverse")
     */
    public function customerManagementPanelInverse(UrlGeneratorInterface $urlGenerator, SpecialistRepository $specialistRepository, ReservationRepository $reservationRepository): Response
    {
        if ($this->isGranted('IS_ANONYMOUS')) {
            return new RedirectResponse($urlGenerator->generate('home'));
        }

        $specialist = $specialistRepository->findOneBy(['email' => $this->getUser()->getUsername()]);
        $reservations = $reservationRepository->getAllPastReservationsBySpecialist($specialist);

        return $this->render('specialist/customermanagement.html.twig', [
            'specialist' => $specialist,
            'reservations' => $reservations,
            'begunReservations' => null,
            'alertMessage' => null
        ]);
    }


}

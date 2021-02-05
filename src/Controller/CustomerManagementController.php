<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerManagementController extends AbstractController
{
    /**
     * @Route("/customer/management", name="customer_management")
     */
    public function customerManagementPanel(UrlGeneratorInterface $urlGenerator, SpecialistRepository $specialistRepository, ReservationRepository $reservationRepository): Response
    {
        if($this->isGranted('IS_ANONYMOUS')){
            return new RedirectResponse($urlGenerator->generate('home'));
        }

        $specialist = $specialistRepository->findOneBy(['email' => $this->getUser()->getUsername()]);
        $reservations = $reservationRepository->getAllUpcomingValidReservationsBySpecialist($specialist);

        return $this->render('customer_management/index.html.twig', [
            'specialist' => $specialist,
            'reservations' => $reservations
        ]);
    }


}

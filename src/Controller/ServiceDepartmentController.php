<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ServiceDepartmentController extends AbstractController
{

    /**
     * @Route("/servicedeparatment/", name="service_department")
     */
    public function serviceDepartmentPanel(UrlGeneratorInterface $urlGenerator, SpecialistRepository $specialistRepository, ReservationRepository $reservationRepository): Response
    {
        if($this->isGranted('IS_ANONYMOUS')){
            return new RedirectResponse($urlGenerator->generate('home'));
        }

        $specialists = $specialistRepository->findAll();

        return $this->render('service_department/screen.html.twig', [
            'specialists' => $specialists,
            'reservationRepo' => $reservationRepository
        ]);
    }
}

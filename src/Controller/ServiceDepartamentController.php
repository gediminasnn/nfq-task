<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ServiceDepartamentController extends AbstractController
{
    private $specialistRepository;
    private $reservationRepository;

    public function __construct(SpecialistRepository $specialistRepository, ReservationRepository $reservationRepository)
    {
        $this->specialistRepository = $specialistRepository;
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @Route("/customers/", name="service_department")
     */
    public function serviceDepartmentPanel(UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->isGranted('IS_ANONYMOUS')){
            return new RedirectResponse($urlGenerator->generate('home'));
        }

        $specialists = $this->specialistRepository->findAll();

        return $this->render('service_department/index.html.twig', [
            'specialists' => $specialists,
            'reservationRepo' => $this->reservationRepository
        ]);
    }
}

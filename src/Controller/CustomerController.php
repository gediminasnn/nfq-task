<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerController extends AbstractController
{

    private $reservationRepository;
    private $specialistRepository;

    /**
     * CustomerController constructor.
     */
    public function __construct(ReservationRepository $reservationRepository, SpecialistRepository $specialistRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->specialistRepository = $specialistRepository;
    }


    /**
     * @Route("/", name="home")
     */
    public function newVisitButtonForCustomer(UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->isGranted('ROLE_SPECIALIST')){
            return new RedirectResponse($urlGenerator->generate('specialist'));
        }
        return $this->render('home/index.html.twig');
    }


}
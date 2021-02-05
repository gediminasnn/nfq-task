<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomerController extends AbstractController
{
    private $customerRepository;
    /**
     * CustomerController constructor.
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

//  TODO : make working newReservationPanel method
    /**
     * @Route("/new", name="new_reservation_panel")
     */
    public function newReservationPanel(UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->isGranted('ROLE_SPECIALIST')){
            return new RedirectResponse($urlGenerator->generate('customer_management'));
        }
        return $this->render('customer/index.html.twig');
    }

    /**
     * @Route("/new/{code}", name="reservation_panel")
     */
    public function reservationPanel($code): Response
    {
        $customer = $this->customerRepository->findOneBy(['code' => $code]);

        return $this->render('customer/index.html.twig', [
            'customer' => $customer
        ]);
    }
}
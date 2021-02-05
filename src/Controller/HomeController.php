<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function newVisitButtonForCustomer(UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->isGranted('ROLE_SPECIALIST')){
            return new RedirectResponse($urlGenerator->generate('customer_management'));
        }
        return $this->render('home/index.html.twig');
    }
}

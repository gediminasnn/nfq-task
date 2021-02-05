<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ServiceDepartamentController extends AbstractController
{

    /**
     * @Route("/customers/", name="service_department")
     */
    public function serviceDepartamentPanel(UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->isGranted('IS_ANONYMOUS')){
            return new RedirectResponse($urlGenerator->generate('home'));
        }
        return $this->render('service_department/index.twig');
    }
}

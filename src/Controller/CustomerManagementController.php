<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerManagementController extends AbstractController
{
    /**
     * @Route("/customer/management", name="customer_management")
     */
    public function customerManagementPanel(): Response
    {
        return $this->render('customer_management/index.html.twig');
    }

    /**
     * @Route("/customers/", name="service_department")
     */
    public function serviceDepartamentPanel(): Response
    {
        return $this->render('service_department/index.twig');
    }
}

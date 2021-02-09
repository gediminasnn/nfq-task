<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\CodeGenerator\CodeGeneratorService;
use App\Service\Reservation\ReservationService;
use App\Service\Specialist\SpecialistService;
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
     * @Route("/reservations/new", name="new_reservation_panel")
     * @param UrlGeneratorInterface $urlGenerator
     * @param CodeGeneratorService $codeGenerator
     * @param ReservationService $reservationService
     * @return Response
     */
    public function newReservationPanel(UrlGeneratorInterface $urlGenerator, CodeGeneratorService $codeGenerator, SpecialistService $specialistService): Response
    {
        $em = $this->getDoctrine()->getManager();

        $customer = new Customer();
        $customerRepository = $this->getDoctrine()->getRepository(Customer::class);
        $code = $codeGenerator->generateCode($customerRepository);
        $customer->setCode($code);

        $em->persist($customer);




//        $reservationTimeInterval = 30;

        $reservation = new Reservation();
        $reservationRepository = $this->getDoctrine()->getRepository(Reservation::class);
        $code = $codeGenerator->generateCode($reservationRepository);
        $reservation->setCode($code);
        $reservation->setState("pending");
        $reservation->setCustomer($customer);

        $earliestSpecialist = $specialistService->findSpecialistWithEarliestTime();

        $earliestTime = $specialistService->findEarliestFreeTime($earliestSpecialist)->format("Y-m-d H:i:s");


        $dateStart = new \DateTime($earliestTime);
        $dateEnd = new \DateTime($earliestTime);
        $dateEnd = $dateEnd->add(new \DateInterval("PT30M"));
//        dd($dateStart);
        $reservation->setStartTime($dateStart);
        $reservation->setEndTime($dateEnd);


        $reservation->setSpecialist($earliestSpecialist);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();


//        return new RedirectResponse($urlGenerator->generate('home'));
        return new RedirectResponse($urlGenerator->generate('reservation_panel',['reservationCode' => $reservation->getCode()]));
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
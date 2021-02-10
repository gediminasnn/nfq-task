<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Service\Reservation\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationController extends AbstractController
{

    private $reservationRepository;
    private $urlGenerator;


    /**
     * ReservationController constructor.
     * @param ReservationRepository $reservationRepository
     */
    public function __construct(ReservationRepository $reservationRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->reservationRepository = $reservationRepository;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @Route("/reservations/update/{reservationCode}/begun", name="begin_reservation")
     * @param $reservationCode
     * @param ReservationService $reservationService
     * @return Response
     */
    public function changeReservationStateToBegun($reservationCode, ReservationService $reservationService): Response
    {
        if (!$this->isGranted('ROLE_SPECIALIST')) {
            return new RedirectResponse($this->urlGenerator->generate('home'));
        }

        $reservationToUpdate = $this->reservationRepository->findOneBy(['code' => $reservationCode]);
        if (!$reservationToUpdate) {
            throw new NotFoundHttpException("The reservation (code :{$reservationCode}) doesn't exist");
        }

        $currentSpecialist = $reservationToUpdate->getSpecialist();
        $upcomingValidReservations = $this->reservationRepository->findAllUpcomingValidReservationsBySpecialist($currentSpecialist);

        if ($reservationService->checkIfBegunReservationExist($upcomingValidReservations) === false) {
            $this->reservationRepository->updateReservationStateToBegun($reservationToUpdate);
            $alertMessage = null;
        }
        else {
            $alertMessage = "Active appointment already exist!";
        }

        return new RedirectResponse($this->urlGenerator->generate('customer_management',['alertMessage' => $alertMessage]));
    }

    /**
     * @Route("/reservations/end/{reservationCode}", name="end_reservation")
     * @param $reservationCode
     * @return Response
     */
    public function changeReservationStateToEnded($reservationCode): Response
    {
        $reservation = $this->reservationRepository->findOneBy(['code' => $reservationCode]);
        if (!$reservation) {
            throw new NotFoundHttpException("The reservation (code :{$reservationCode}) doesn't exist");
        }
        $this->reservationRepository->updateReservationStateToEnded($reservation);

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * @Route("/reservations/cancel/{reservationCode}", name="cancel_reservation")
     * @param $reservationCode
     * @return Response
     */
    public function changeReservationStateToCanceled($reservationCode): Response
    {
        $reservation = $this->reservationRepository->findOneBy(['code' => $reservationCode]);
        if (!$reservation) {
            throw new NotFoundHttpException("The reservation (code :{$reservationCode}) doesn't exist");
        }
        $this->reservationRepository->updateReservationStateToCanceled($reservation);

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * @Route("/reservations/delete/{reservationCode}", name="delete_reservation")
     * @param $reservationCode
     * @return Response
     */
    public function deleteReservation($reservationCode): Response
    {
        $reservation = $this->reservationRepository->findOneBy(['code' => $reservationCode]);
        if (!$reservation) {
            throw new NotFoundHttpException("The reservation (code :{$reservationCode}) doesn't exist");
        }
        $this->reservationRepository->removeReservation($reservation);

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * @Route("/reservations/update/past", name="update_past_reservations")
     * @param UrlGeneratorInterface $urlGenerator
     * @param ReservationService $reservationService
     * @return Response
     */
    public function updatePastReservations(UrlGeneratorInterface $urlGenerator, ReservationService $reservationService): Response
    {
        if ($this->isGranted('IS_ANONYMOUS')) {
            return new RedirectResponse($urlGenerator->generate('home'));
        }

        $reservationService->updatePastReservations();

        return new RedirectResponse($urlGenerator->generate('customer_management'));
    }

}

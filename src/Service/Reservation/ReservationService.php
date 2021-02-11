<?php


namespace App\Service\Reservation;


use App\Entity\Reservation;
use App\Repository\ReservationRepository;

class ReservationService
{
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @param Reservation[] $reservations
     * @return bool
     */
    public function checkIfBegunReservationExist(array $reservations): bool
    {
        $doesBegunReservationExist = false;
        foreach ($reservations as $reservation) {
            if ($reservation->getState() === "begun") {
                $doesBegunReservationExist = true;
                break;
            }
        }
        return $doesBegunReservationExist;
    }

//unused method
//    public function updatePastReservations(): void
//    {
//        $qb = $this->reservationRepository->createQueryBuilder('r');
//
//        $reservations =
//            $qb->where('r.state = ?1')
//                ->orwhere('r.state = ?2')
//                ->andWhere('r.endTime < :now')
//                ->setParameters(['now' => date("Y-m-d H:i:s"), 1 => 'pending', 2 => 'begun'])
//                ->orderBy('r.startTime', 'ASC')
//                ->getQuery()
//                ->getResult();
//
//        foreach ($reservations as $reservation) {
//            if ($reservation->getState() === "begun") {
//                $reservation->setState("ended");
//            } else if ($reservation->getState() === "pending") {
//                $reservation->setState("canceled");
//            }
//            $this->entityManager->persist($reservation);
//        }
//        $this->entityManager->flush();
//    }

    public function findReservationQueuePosition(Reservation $reservation): ?int
    {
        $result = null;
        $reservations = $this->reservationRepository->findAllUpcomingPendingReservationsBySpecialist($reservation->getSpecialist());
        $count = count($reservations);
        for ($i = 1; $i <= $count; $i++) {
            if ($reservations[$i - 1]->getCode() === $reservation->getCode()) {
                $result = $i;
                break;
            }
        }
        return $result;
    }
}
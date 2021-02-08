<?php


namespace App\Service\Reservation;


use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    private $entityManager;
    private $reservationRepository;

    public function __construct(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository)
    {
        $this->entityManager = $entityManager;
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


    public function endPastReservations(): void
    {
        $qb = $this->reservationRepository->createQueryBuilder('r');

        $reservations =
            $qb->where('r.state = ?1')
            ->orwhere('r.state = ?2')
            ->andWhere('r.endTime < :now')
            ->setParameters(['now' => date("Y-m-d H:i:s"), 1 => 'pending', 2=> 'begun'])
            ->orderBy('r.startTime', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($reservations as $reservation) {
            if ($reservation->getState() === "begun") {
                $reservation->setState("ended");
            }
            else if ($reservation->getState() === "pending") {
                $reservation->setState("canceled");
            }
            $this->entityManager->persist($reservation);
        }
        $this->entityManager->flush();
    }

}
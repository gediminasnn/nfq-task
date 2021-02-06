<?php


namespace App\Service\Reservation;


use App\Entity\Reservation;

class ReservationService
{
    /**
     * @param Reservation[] $reservations
     * @return bool
     */
    public function checkIfBegunReservationExist(array $reservations): bool
    {
        $doesBegunReservationExist = false;
        foreach ($reservations as $reservation)
        {
            if($reservation->getState() === "begun")
            {
                $doesBegunReservationExist = true;
                break;
            }
        }
        return $doesBegunReservationExist;
    }

}
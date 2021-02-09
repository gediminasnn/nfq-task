<?php


namespace App\Service\Specialist;


use App\Entity\Reservation;
use App\Entity\Specialist;
use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;

class SpecialistService
{
    private $reservationRepository;
    private $specialistRepository;

    public function __construct(ReservationRepository $reservationRepository, SpecialistRepository $specialistRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->specialistRepository = $specialistRepository;
    }

    /**
     * @param Specialist $specialist
     * @param int $reservationTimeInMinutes
     */
    public function findEarliestFreeTime(Specialist $specialist, $reservationTimeInMinutes = 30)
    {
        /* @var $allSpecialistReservations Reservation[] */
        /* @var $reservation Reservation */

        $allSpecialistReservations = $this->reservationRepository->findAllUpcomingValidReservationsBySpecialist($specialist);

        $reservationCount = count($allSpecialistReservations);

        if($reservationCount === 0)
        {
            $earliestFreeTimeStartTime = new \DateTime("now");
        } else {
            $earliestFreeTimeStartTime = $allSpecialistReservations[$reservationCount-1]->getEndTime();
        }


        $reservationTimeInterval = new \DateInterval("PT{$reservationTimeInMinutes}M");

        for($i = 0; $i < $reservationCount-1; $i++){

            $date1 = new \DateTime($allSpecialistReservations[$i]->getEndTime()->format("Y-m-d H:i:s"));
            $date2 = new \DateTime($allSpecialistReservations[$i+1]->getStartTime()->format("Y-m-d H:i:s"));

            $date1->add($reservationTimeInterval);

            if($date1 <= $date2)
            {
                $earliestFreeTimeStartTime = $allSpecialistReservations[$i]->getEndTime();
                break;
            }
        }
        return $earliestFreeTimeStartTime;
    }

    public function findSpecialistWithEarliestTime(): Specialist
    {
        /* @var $allSpecialistReservations Reservation[] */
        /* @var $reservation Reservation */

        $specialists = $this->specialistRepository->findAll();
        $specialistCount = count($specialists);

        $specialist = $specialists[0];
        $specialistsOneEarliestFreeTime = $this->findEarliestFreeTime($specialists[0])->format("Y-m-d H:i:s");

        for($i = 1; $i<$specialistCount ; $i++){

            $specialistsTwoEarliestFreeTime = $this->findEarliestFreeTime($specialists[$i])->format("Y-m-d H:i:s");

            if($specialistsOneEarliestFreeTime > $specialistsTwoEarliestFreeTime)
            {
                $specialistsOneEarliestFreeTime = $specialistsTwoEarliestFreeTime;
                $specialist = $specialists[$i];
            }
        }

        return $specialist;
    }
}
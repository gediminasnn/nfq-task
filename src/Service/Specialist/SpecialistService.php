<?php

namespace App\Service\Specialist;


use App\Entity\Reservation;
use App\Entity\Specialist;
use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;
use App\Service\DateTime\DateTimeService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SpecialistService
{
    private $reservationRepository;
    private $specialistRepository;
    private $dateTimeService;

    public function __construct(ReservationRepository $reservationRepository, SpecialistRepository $specialistRepository, DateTimeService $dateTimeService)
    {
        $this->reservationRepository = $reservationRepository;
        $this->specialistRepository = $specialistRepository;
        $this->dateTimeService = $dateTimeService;
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

        if ($reservationCount === 0) {
            $earliestFreeTimeStartTime = $this->dateTimeService->makeDateTimeToUpcomingEightAM(new \DateTime("now"));

        } else {
            $earliestFreeTimeStartTime = $allSpecialistReservations[$reservationCount - 1]->getEndTime()->format("Y-m-d H:i:s");
            $earliestFreeTimeStartTime = $this->dateTimeService->makeDateTimeToUpcomingEightAM(new \DateTime($earliestFreeTimeStartTime));
        }


        $reservationTimeInterval = new \DateInterval("PT{$reservationTimeInMinutes}M");

        for ($i = 0; $i < $reservationCount - 1; $i++) {

            $date1 = new \DateTime($allSpecialistReservations[$i]->getEndTime()->format("Y-m-d H:i:s"));
            $date2 = new \DateTime($allSpecialistReservations[$i + 1]->getStartTime()->format("Y-m-d H:i:s"));

            $date1->add($reservationTimeInterval);


            if ($this->dateTimeService->makeDateTimeToUpcomingEightAM($date1)) {
                $date1 = $this->dateTimeService->makeDateTimeToUpcomingEightAM($date1);
                if ($date1 <= $date2) {
                    $earliestFreeTimeStartTime = $allSpecialistReservations[$i]->getEndTime();
                    break;
                }
            } else if ($date1 <= $date2) {
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
        if (!$specialists) {
            throw new NotFoundHttpException("There are no specialist available at the moment");
        }
        $specialistCount = count($specialists);

        $specialist = $specialists[0];

        $specialistsOneEarliestFreeTime = $this->findEarliestFreeTime($specialists[0])->format("Y-m-d H:i:s");

        for ($i = 1; $i < $specialistCount; $i++) {

            $specialistsTwoEarliestFreeTime = $this->findEarliestFreeTime($specialists[$i])->format("Y-m-d H:i:s");

            if ($specialistsOneEarliestFreeTime > $specialistsTwoEarliestFreeTime) {
                $specialistsOneEarliestFreeTime = $specialistsTwoEarliestFreeTime;
                $specialist = $specialists[$i];
            }
        }

        return $specialist;
    }
}
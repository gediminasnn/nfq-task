<?php


namespace App\Service\DateTime;


class DateTimeService
{
    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isTimeAfterFourThirtyPM(\DateTime $dateTime): bool
    {
        $thisDay = $dateTime;
        $thisDay = $thisDay->setTime(0,0,0,0);
        $thisDay->add(new \DateInterval("PT16H30M"))->format("Y-m-d H:i:s");
        $dateTime->format("Y-m-d H:i:s");

        return $dateTime > $thisDay;
    }

    public function isTimeBeforeEightAM(\DateTime $dateTime): bool
    {
        $thisDay = $dateTime;
        $thisDay = $thisDay->setTime(0,0,0,0);
        $thisDay->add(new \DateInterval("PT8H"))->format("Y-m-d H:i:s");
        $dateTime->format("Y-m-d H:i:s");

        return $dateTime < $thisDay;
    }

    public function makeDateTimeToUpcomingEightAM(\DateTime $dateTime): \DateTime
    {
        if ($this->isTimeAfterFourThirtyPM($dateTime)) {
            $tomorrowEightAM = $dateTime;
            $tomorrowEightAM = $tomorrowEightAM->setTime(0,0,0,0);
            $tomorrowEightAM->add(new \DateInterval("P1DT8H"))->format("Y-m-d H:i:s");
            return $tomorrowEightAM;
        }

        if ($this->isTimeBeforeEightAM($dateTime)) {
            $todayEightAM = $dateTime;
            $todayEightAM = $todayEightAM->setTime(0,0,0,0);
            $todayEightAM->add(new \DateInterval("PT8H"))->format("Y-m-d H:i:s");
            return $todayEightAM;
        }
        return $dateTime;
    }
}
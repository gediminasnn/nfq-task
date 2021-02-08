<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Specialist;
use App\Service\CodeGenerator\CodesInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository implements CodesInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findAllEntityCodes(): array
    {
        return $this->createQueryBuilder('r')
            ->select('code')
            ->getQuery()
            ->getResult();
    }

    public function findFiveUpcomingPendingReservationsBySpecialist(Specialist $specialist): ?array
    {

        return $this->createQueryBuilder('r')
            ->where('r.specialist = :specId')
            ->andWhere('r.state = :state1')
            ->andWhere('r.endTime > :now')
            ->setParameters(['specId' => $specialist, 'state1' => 'pending', 'now' => date("Y-m-d H:i:s")])
            ->orderBy('r.startTime', 'ASC')
            ->setMaxResults('5')
            ->getQuery()
            ->getResult();
    }


    public function findAllUpcomingValidReservationsBySpecialist(Specialist $specialist): ?array
    {
        return $this->createQueryBuilder('r')
            ->where('r.state = :state1')
            ->orWhere('r.state = :state2')
            ->andWhere('r.specialist = :specId')
            ->andWhere('r.endTime > :now')
            ->setParameters(['specId' => $specialist, 'state1' => 'pending', 'state2' => 'begun', 'now' => date("Y-m-d H:i:s")])
            ->orderBy('r.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findAllUpcomingPendingReservationsBySpecialist(Specialist $specialist): ?array
    {
        return $this->createQueryBuilder('r')
            ->where('r.specialist = :specId')
            ->andWhere('r.state = :state1')
            ->andWhere('r.endTime > :now')
            ->setParameters(['specId' => $specialist, 'state1' => 'pending', 'now' => date("Y-m-d H:i:s")])
            ->orderBy('r.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllBegunReservationBySpecialist(Specialist $specialist): ?array
    {
        return $this->createQueryBuilder('r')
            ->where('r.specialist = :specId')
            ->andWhere('r.state = :state1')
            ->andWhere('r.endTime > :now')
            ->setParameters(['specId' => $specialist, 'state1' => 'begun', 'now' => date("Y-m-d H:i:s")])
            ->orderBy('r.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllUpcomingValidReservations(): ?array
    {

        return $this->createQueryBuilder('r')
            ->where('r.state = :state1')
            ->orWhere('r.state = :state2')
            ->setParameters(['state1' => 'pending', 'state2' => 'begun'])
            ->orderBy('r.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function removeReservation(Reservation $reservation): void
    {
        $em = $this->getEntityManager();

        //TODO: figure out if this is correct
        try {
            $em->remove($reservation);
        } catch (ORMException $e) {
            echo "Exception found - " . $e->getMessage();
        }

        try {
            $em->flush();
        } catch (\Exception $e) {
            echo "Exception found - " . $e->getMessage();
        }

    }

    public function findReservationQueuePosition(Reservation $reservation): ?int
    {
        $result = null;
        $reservations = $this->findAllUpcomingPendingReservationsBySpecialist($reservation->getSpecialist());
        $count = count($reservations);
        for ($i = 1; $i <= $count; $i++) {
            if ($reservations[$i - 1]->getCode() === $reservation->getCode()) {
                $result = $i;
                break;
            }
        }
        return $result;
    }

    public function updateReservationStateToBegun(Reservation $reservation): void
    {
        $qb = $this->createQueryBuilder('r');
        $q = $qb->update()
            ->set('r.state', '?1')
            ->where('r.code = ?2')
            ->setParameter(1, 'begun')
            ->setParameter(2, $reservation->getCode())
            ->getQuery();
        $q->execute();
    }

    public function updateReservationStateToCanceled(Reservation $reservation): void
    {
        $qb = $this->createQueryBuilder('r');
        $q = $qb->update()
            ->set('r.state', '?1')
            ->where('r.code = ?2')
            ->setParameter(1, 'canceled')
            ->setParameter(2, $reservation->getCode())
            ->getQuery();
        $q->execute();
    }

    public function updateReservationStateToEnded(Reservation $reservation): void
    {
        $qb = $this->createQueryBuilder('r');
        $q = $qb->update()
            ->set('r.state', '?1')
            ->where('r.code = ?2')
            ->setParameter(1, 'ended')
            ->setParameter(2, $reservation->getCode())
            ->getQuery();
        $q->execute();
    }

    public function findAllPastReservations(Specialist $specialist): ?array
    {
        return $this->createQueryBuilder('r')
            ->where('r.specialist = :specId')
            ->andWhere('r.endTime < :now')
            ->setParameters(['specId' => $specialist, 'now' => date("Y-m-d H:i:s")])
            ->orderBy('r.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

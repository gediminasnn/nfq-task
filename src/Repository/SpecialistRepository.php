<?php

namespace App\Repository;

use App\Entity\Specialist;
use App\Service\CodeGenerator\CodesInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Specialist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialist[]    findAll()
 * @method Specialist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialistRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, CodesInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialist::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Specialist) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findAllEntityCodes(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.code')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Specialist[] Returns an array of Specialist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specialist
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Reservation;
use App\Entity\Specialist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //Creating 3 Specialists
        for ($i = 1; $i <= 3; $i++) {
            $specialist = new Specialist();
            $specialist->setEmail("specialist{$i}@gmail.com");
            $specialist->setCode("SPCLS{$i}");
            $specialist->setPassword($this->passwordEncoder->encodePassword(
                $specialist,
                'specialist'
            ));
            $specialist->setRoles(['ROLE_SPECIALIST']);
            $manager->persist($specialist);

//            //Creating 6 Reservations with 6 different customers with current loop cycle specialist
//            for ($y = 1; $y <= 6; $y++) {
//                $customer = new Customer();
//                $customer->setCode("{$i}RCST{$y}");
//                $manager->persist($customer);
//
//                $reservation = new Reservation();
//                $reservation->setCode("{$i}RRSR{$y}");
//                $reservation->setCustomer($customer);
//                $reservation->setSpecialist($specialist);
//                $date = new \DateTime("now");
//                $reservation->setStartTime($date->sub(new \DateInterval("P{$y}D")));
//                $date = new \DateTime("now");
//                $reservation->setEndTime($date->sub(new \DateInterval("PT23H15M")));
//                $manager->persist($reservation);
//
//
//
//                $customer = new Customer();
//                $customer->setCode("{$i}CSTM{$y}");
//                $manager->persist($customer);
//
//                $reservation = new Reservation();
//                $reservation->setCode("{$i}RSRV{$y}");
//                $reservation->setCustomer($customer);
//                $reservation->setSpecialist($specialist);
//                $date2 = new \DateTime("now");
//                $reservation->setStartTime($date2->add(new \DateInterval("P{$y}D")));
//                $date2 = new \DateTime("now");
//                $reservation->setEndTime($date2->add(new \DateInterval("P{$y}DT45M")));
//                $manager->persist($reservation);
//            }
        }

        $manager->flush();

        // $product = new Product();
        // $manager->persist($product);

    }
}

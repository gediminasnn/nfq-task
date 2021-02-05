<?php

namespace App\DataFixtures;

use App\Entity\Specialist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SpecialistsFixtures extends Fixture
{
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        for($i = 1; $i<=3;$i++)
        {
            $user = new Specialist();
            $user->setEmail("specialist{$i}@gmail.com");
            $user->setCode("SPCLST{$i}");
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'specialist'
            ));
            $user->setRoles(['ROLE_SPECIALIST']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

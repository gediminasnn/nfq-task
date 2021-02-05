<?php

namespace App\DataFixtures;

use App\Entity\Specialist;
use App\Service\CodesInterface;
use App\Service\CodeGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SpecialistsFixtures extends Fixture
{
    private $repository;
    private $codeGenerator;
    private $passwordEncoder;


    public function __construct(CodesInterface $specialist, CodeGenerator $codeGenerator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->repository = $specialist;
        $this->codeGenerator = $codeGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        for($i = 1; $i<=3;$i++)
        {
            $user = new Specialist();
            $user->setEmail("specialist{$i}@gmail.com");
            $user->setCode($this->codeGenerator->generateCode($this->repository));
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

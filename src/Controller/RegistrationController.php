<?php

namespace App\Controller;

use App\Entity\Specialist;
use App\Form\RegistrationFormType;
use App\Repository\SpecialistRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\CodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, CodeGenerator $codeGenerator, SpecialistRepository $repository): Response
    {
        $user = new Specialist();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setCode($codeGenerator->generateCode($repository));
            $user->setRoles(['ROLE_USER','ROLE_SPECIALIST']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


// TODO: Figure out why this code doesn't pass request normally
//
//    /**
//     * @Route("/registerr", name="app_registerr")
//     */
//    public function registerr(Request $request,UserPasswordEncoderInterface $passwordEncoder, CodeGenerator $codeGenerator, SpecialistRepository $repository): Response
//    {
//        $specialist = new Specialist();
//        $form = $this->createForm(RegistrationFormTypePrototype::class, $specialist);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $form = $form->getData();
//
//            // encode the plain password
//            $specialist->setEmail();
//            $specialist->setPassword(
//                $passwordEncoder->encodePassword(
//                    $specialist, $specialist->getPassword()
//                )
//            );
//            $specialist->setCode($codeGenerator->generateCode($repository));
//            $specialist->setRoles(['ROLE_USER','ROLE_SPECIALIST']);
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($specialist);
//            $entityManager->flush();
//            // do anything else you need here, like send an email
//
//            return $this->redirectToRoute('app_login');
//        }
//
//        return $this->render('security/register.html.twig', [
//            'registrationForm' => $form->createView(),
//        ]);
//    }
}

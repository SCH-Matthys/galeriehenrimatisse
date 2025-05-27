<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }

    #[Route("/register", name: "app_register")]
    public function register(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($userPasswordHasherInterface->hashPassword($user,$form->get("plainPassword")->getData()));
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();
            $this->addFlash("success", "Votre compte à bien été créé.");
            return $this->redirectToRoute("app_login");
        }
        return $this->render("security/register.html.twig", [
            "registerForm" => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

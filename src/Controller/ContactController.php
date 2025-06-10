<?php

namespace App\Controller;

use App\Form\ContactForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $email = (new Email())
                ->from("contact.galerie.henri.matisse@gmail.com")
                ->to("contact.galerie.henri.matisse@gmail.com")
                ->subject("Nouveau message de contact")
                ->text(
                    "Nom : {$data['firstName']}\n".
                    "Nom : {$data['lastName']}\n".
                    "Adresse mail : {$data['email']}\n".
                    "N° de téléphone : {$data['phone']}\n".
                    "Message : {$data['message']}\n"
                );
                $mailer->send($email);

                return $this->redirectToRoute("app_contact");
        }

        return $this->render('contact/contact.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
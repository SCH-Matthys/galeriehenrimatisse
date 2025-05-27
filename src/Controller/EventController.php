<?php

namespace App\Controller;

use App\Entity\EventArticle;
use App\Form\EventArticleTypeForm;
use App\Repository\EventArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\Event;

final class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(EventArticleRepository $eventRepository): Response
    {   
        $events = $eventRepository->findAll();
        return $this->render('event/event.html.twig', [
            "events" => $events,
        ]);
    }

    #[Route("/add/event", name: "add_event")]
    public function addEvent(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $event = new EventArticle();
        $form = $this->createForm(EventArticleTypeForm::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $event->setDate(new \DateTimeImmutable());   
            $entityManagerInterface->persist($event);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'événement à bien été ajouté.");
            return $this->redirectToRoute("app_event");
        }
        return $this->render("event/eventAdd.html.twig", [
            "eventForm" => $form->createView(),
        ]);
    }

    #[Route("/edit/event/{id}", name: "edit_event")]
    public function editEvent(EventArticle $event, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(EventArticleTypeForm::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($event);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'événement à été modifié avec succes.");
            return $this->redirectToRoute("app_event");
        }
        return $this->render("event/eventEdit.html.twig", [
            "eventForm" => $form->createView(),
        ]);
    }

    #[Route("/delete/event/{id}", name: "delete_event")]
    public function deleteEvent(EventArticle $event, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->isCsrfTokenValid("SUP".$event->getId(),$request->get("_token"))){
            $entityManagerInterface->remove($event);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'événement à bien été supprimé.");
            return $this->redirectToRoute("app_event");
        }
        return $this->redirectToRoute("app_event");
    }

    #[Route("/eventDetails/{id}", name:"details_event")]
    public function detailsEvent( EventArticle $event){

        return $this->render("event/eventDetails.html.twig", [
            "event" => $event,
        ]);
    }
}

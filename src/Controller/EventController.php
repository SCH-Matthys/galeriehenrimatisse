<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\EventArticle;
use App\Form\CommentForm;
use App\Form\EventArticleTypeForm;
use App\Repository\EventArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function detailsEvent( EventArticle $event, Security $security, Request $request, EntityManagerInterface $entityManagerInterface): Response{

        $comment = new Comment();
        $comment->setEvent($event);
        $comment->setUser($security->getUser());
        $comment->setDate(new DateTimeImmutable());

        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($comment);
            $entityManagerInterface->flush();

            $this->addFlash("success", "Votre commentaire à bien été ajouté");
            return $this->redirectToRoute("details_event", ["id" => $event->getId()]);
        }

        return $this->render("event/eventDetails.html.twig", [
            "event" => $event,
            'comments' => $event->getComments(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{id}/comment/{commentId}', name: 'app_editComment')]
    public function editComment(Request $request, EntityManagerInterface $entityManager, Security $security, EventArticle $event, Comment $comment): Response
    {
        $user = $security->getUser();

        if ($comment->getUser() !== $user) {
            $this->addFlash('danger', 'Vous ne pouvez pas modifier ce commentaire.');
            return $this->redirectToRoute('details_event', ['id' => $event->getId()]);
        }

        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire a été modifié.');
            return $this->redirectToRoute('details_event', ['id' => $event->getId()]);
        }

        return $this->redirectToRoute('details_event', ['id' => $event->getId()]);
    }

    #[Route('/event/{id}/comment/{commentId}/delete', name: 'app_deleteComment')]
    public function deleteComment(Request $request, EntityManagerInterface $entityManager, Comment $comment, EventArticle $event): Response
    {
        if($this->isCsrfTokenValid("SUP".$comment->getId(),$request->get("_token"))){
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash("success", "Le commentaire à bien été supprimé.");
            return $this->redirectToRoute("details_event", ['id' => $event->getId()]);
        }
        return $this->redirectToRoute("details_event", ['id' => $event->getId()]);
    }
}

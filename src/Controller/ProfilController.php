<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Gallery;
use App\Entity\User;
use App\Form\ArtworkForm;
use App\Form\GalleryForm;
use App\Repository\ArtworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Security $security, ArtworkRepository $artworkRepository): Response
    {
        $user = $security->getUser();
        $artworks = $artworkRepository->findAll();

        return $this->render('profil/profil.html.twig', [
            "user" => $user,
            "artworks" => $artworks,
        ]);
    }
 
    #[Route('/profil/createGallery', name: 'app_createGallery')]
    public function createGallery(Request $request, Security $security, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $security->getUser(); 

        if($user->getGallery()){
            $this->addFlash('warning', 'Vous avez déjà une galerie.');
            return $this->redirectToRoute('app_profil');
        }

        $gallery = new Gallery();
        $gallery->setUser($user);
        $entityManagerInterface->persist($gallery);
        $entityManagerInterface->flush();

        $this->addFlash("success", "Votre galerie à bien été créée.");

        return $this->redirectToRoute("app_profil");

        return $this->render('profil/profil.html.twig', [
            "user" => $user,
        ]);
    }

    #[Route("/profil/add", name: "add_artwork")]
    public function addArtwork(Request $request, EntityManagerInterface $entityManagerInterface, Security $security): Response
    {
        $user = $security->getUser();
        $gallery = $user->getGallery();

        if(!$gallery){
            $this->addFlash("warning", "Vous devez avoir une galerie afin de pouvoir ajouter une oeuvre.");
            return $this->redirectToRoute("app_profil");
        }

        $artwork = new Artwork();
        $artwork->setGallery($gallery);
        $form = $this->createForm(ArtworkForm::class, $artwork);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){ 
            $entityManagerInterface->persist($artwork);
            $entityManagerInterface->flush();
            $this->addFlash("success", "Votre oeuvre à bien été ajouté à votre galerie.");
            return $this->redirectToRoute("app_profil");
        }
        return $this->render("profil/addArtwork.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/profil/edit/{id}", name: "edit_oeuvre")]
    public function editArtwork(Artwork $artwork, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(ArtworkForm::class, $artwork);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($artwork);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'oeuvre à été modifié avec succes.");
            return $this->redirectToRoute("app_profil");
        }
        return $this->render("profil/editArtwork.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    // #[Route("/profil/delete/{id}", name: "delete_artwork")]
    // public function deleteEvent(EventArticle $event, Request $request, EntityManagerInterface $entityManagerInterface): Response
    // {
    //     if($this->isCsrfTokenValid("SUP".$event->getId(),$request->get("_token"))){
    //         $entityManagerInterface->remove($event);
    //         $entityManagerInterface->flush();
    //         $this->addFlash("success", "L'événement à bien été supprimé.");
    //         return $this->redirectToRoute("app_event");
    //     }
    //     return $this->redirectToRoute("app_event");
    // }
}

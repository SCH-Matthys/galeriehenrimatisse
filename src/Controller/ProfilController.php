<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\ArtworkImage;
use App\Entity\Gallery;
use App\Entity\User;
use App\Form\ArtworkForm;
use App\Form\GalleryForm;
use App\Repository\ArtworkImageRepository;
use App\Repository\ArtworkRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil')]
    public function index(Security $security, User $user, ArtworkImageRepository $artworkImageRepository): Response
    {
        // $user = $security->getUser();
        $images = $artworkImageRepository->findAll();
        // $artworks = $artworkRepository->findAll();

        return $this->render('profil/profil.html.twig', [
            "user" => $user,
            'gallery' => $user->getGallery(),
            "images" => $images,
            // "artworks" => $artworks,
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

    #[IsGranted("ROLE_USER")]
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

        // Cette ligne ajoute une image vide lors de la génération du formulaire
        // $artwork->addArtworkImage(new ArtworkImage());

        $form = $this->createForm(ArtworkForm::class, $artwork);
        $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()){ 
            // foreach ($artwork->getImages() as $image){
            //     $image->setArtwork($artwork);
            // }
            if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $files */
                $files = $form->get('files')->getData();

                foreach ($files as $file) {
                    // dd($file);
                    $image = new ArtworkImage();
                    $image->setImageFile($file); // Ici VichUploaderBundle va gérer le reste
                    $artwork->addArtworkImage($image);
                }
                $entityManagerInterface->persist($artwork);
                $entityManagerInterface->flush();
                $this->addFlash("success", "Votre oeuvre à bien été ajouté à votre galerie.");
                // dd($files);
                return $this->redirectToRoute("app_profil");
            }
            return $this->render("profil/addArtwork.html.twig", [
                "form" => $form->createView(),
            ]);
        // }
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

    #[Route("/profil/delete/{id}", name: "delete_artwork")]
    public function deleteEvent(Artwork $artwork, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->isCsrfTokenValid("SUP".$artwork->getId(),$request->get("_token"))){
            $entityManagerInterface->remove($artwork);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'oeuvre à bien été supprimé.");
            return $this->redirectToRoute("app_profil");
        }
        return $this->redirectToRoute("app_profil");
    }

    
    #[Route("/artwork/{id}", name: "app_artworkDetails")]
    public function artworkDetails(Artwork $artwork)
    {
        return $this->render("profil/artwork.html.twig", [
            "artwork" => $artwork,
        ]);
    }
}

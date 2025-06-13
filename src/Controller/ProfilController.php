<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\ArtworkImage;
use App\Entity\Gallery;
use App\Entity\User;
use App\Form\ArtworkForm;
use App\Form\ArtworkImageForm;
use App\Form\ArtworkNoImgForm;
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
    #[Route('/profil', name: 'app_profill')]
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
 
    #[IsGranted("ROLE_USER")]
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
    #[Route("/profil/{id}/add", name: "add_artwork")]
    public function addArtwork(Request $request, EntityManagerInterface $entityManagerInterface, Security $security, User $user): Response
    {
        // $user = $security->getUser();
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
                return $this->redirectToRoute("app_profil", ["id" => $user->getId()]);
            }
            return $this->render("profil/addArtwork.html.twig", [
                "form" => $form->createView(),
            ]);
        // }
    }

    #[Route("/profil/{id}/edit/{artwork}", name: "edit_oeuvre")]
    public function editArtwork(Request $request, EntityManagerInterface $entityManagerInterface, User $user, Artwork $artwork): Response
    {
        $form = $this->createForm(ArtworkNoImgForm::class, $artwork);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($artwork);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'oeuvre à été modifié avec succes.");
            return $this->redirectToRoute("app_profil", ["id" => $user->getId()]);
        }
        return $this->render("profil/editArtwork.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/profil/{id}/delete/{artwork}", name: "delete_artwork")]
    public function deleteEvent(Request $request, EntityManagerInterface $entityManagerInterface, User $user, Artwork $artwork): Response
    {
        if($this->isCsrfTokenValid("SUP".$artwork->getId(),$request->get("_token"))){
            $entityManagerInterface->remove($artwork);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'oeuvre à bien été supprimé.");
            return $this->redirectToRoute("app_profil", ["id" => $user->getId()]);
        }
        return $this->redirectToRoute("app_profil", ["id" => $user->getId()]);
    }


    
    #[Route("/artwork/{id}", name: "app_artworkDetails")]
    public function artworkDetails(Artwork $artwork)
    {
        return $this->render("profil/artwork.html.twig", [
            "artwork" => $artwork,
        ]);
    }

    #[Route("/artwork/{id}/editImage/{imageName}", name: "app_artworkDetailsEditImage")]
    public function artworkDetailsEditImage(Artwork $artwork, ArtworkImage $imageName, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(ArtworkImageForm::class, $imageName);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($imageName);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'oeuvre à été modifié avec succes.");
            return $this->redirectToRoute("app_artworkDetails", ["id" => $artwork->getId()]);
        }
        return $this->render("profil/editArtwork.html.twig", [
            "form" => $form->createView(),
            "artwork" => $artwork,
            "image" => $imageName
        ]);
    }

    #[Route("/artwork/{id}/deleteImage/{imageName}", name: "app_artworkDetailsDeleteImage")]
    public function artworkDetailsDeleteImage(Artwork $artwork, ArtworkImage $imageName, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if ($this->isCsrfTokenValid('SUP' . $imageName->getId(), $request->get('_token'))) {
            $entityManagerInterface->remove($imageName);
            $entityManagerInterface->flush();
            $this->addFlash("success", "L'oeuvre à bien été supprimé.");
            return $this->redirectToRoute("app_artworkDetails", ["id" => $artwork->getId()]);
        }
        return $this->redirectToRoute("app_artworkDetails", ["id" => $artwork->getId()]);
    }
}

<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index(ArtworkRepository $artworkRepository): Response
    {
        $artworks = $artworkRepository->findRandomArtwork(30);

        return $this->render('gallery/gallery.html.twig', [
            "artworks" => $artworks,
        ]);
    }
}

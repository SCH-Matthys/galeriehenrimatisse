<?php

namespace App\Controller;

use App\Repository\EventArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventArticleRepository $eventArticleRepository): Response
    {
        $events = $eventArticleRepository->findLast(5);
        return $this->render('home/home.html.twig', [
            "events" => $events,
        ]);
    }

    #[Route('/informations', name: 'app_informations')]
    public function information(): Response
    {
        return $this->render('informations/informations.html.twig', [
            
        ]);
    }

    #[Route('/mentionslegales', name: 'app_mentionslegales')]
    public function mentionslegales(): Response
    {
        return $this->render('informations/mentionslegales.html.twig', [
            
        ]);
    }

    #[Route('/politiquedeconfidentialite', name: 'app_politiquedeconfidentialite')]
    public function politiquedeconfidentialite(): Response
    {
        return $this->render('informations/politiquedeconfidentialite.html.twig', [
            
        ]);
    }
}

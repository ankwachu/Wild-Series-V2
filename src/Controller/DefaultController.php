<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EpisodeRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
    */

    public function index(EpisodeRepository $episodeRepository) :Response
    {        
        return $this->render ('index.html.twig', [
            'episodes' => $episodeRepository->findByDate(),
        ]);
    }
}
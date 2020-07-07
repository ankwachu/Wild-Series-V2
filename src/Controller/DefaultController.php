<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Entity\Program;
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
    */

    public function homepage(EpisodeRepository $episodeRepository) :Response
    {        
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy([],
                    ['id' => 'DESC'],
                    3
            );

        
        return $this->render ('homepage/index.html.twig', [
            'programs' => $programs,
            'episodes' => $episodeRepository->findByDateExpiration(),
        ]);
    }
}
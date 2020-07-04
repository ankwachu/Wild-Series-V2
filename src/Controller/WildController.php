<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramSearchType;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * 
     * @Route("/series", name="programs")
     */

    public function index(Request $request, ProgramRepository $programRepository, PaginatorInterface $paginator): Response
    {
        $programs = $paginator->paginate(
            $programRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(ProgramSearchType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()->getName();
            // $programs = $programRepository->search($title);
            $programs = $paginator->paginate(
                $programRepository->search($title),
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries V2',
            'programs' => $programs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug in program\'s table.');
        }

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['slug' => $slug]);
            
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy([
                'program' => $program,
            ]);

        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons'  => $seasons,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @Route("/season/{id<^[0-9-]+$>}", defaults={"id" = null}, name="season")
     */
    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No season has been find in season\'s table.');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);
        $program = $season->getProgram();
        $episodes = $season->getEpisode();
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with ' . $id . ' season, found in Season\'s table.'
            );
        }
        return $this->render('wild/season.html.twig', [
            'season'   => $season,
            'program'  => $program,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @param string $slug The slugger
     * @Route("/episode/{slug}", defaults={"slug" = null},
     *     name="episode")
     */
    public function showEpisode(?string $slug): Response
    {
        $episode = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->findOneBy(['slug' => $slug]);

        if (!$episode) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        $season = $episode->getSeasons();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season'   => $season,
            'program'  => $program,
        ]);
    }

    /**
     * @param string $slug The slugger
     * @Route("/show/actor/{slug}", name="show_actor")
     */
    public function showByActor(Actor $actor): Response
    {
        $program = $actor->getPrograms()->toArray();
        return $this->render(("wild/actor.html.twig"), [
            "actor" => $actor,
            "programs" => $program,
        ]);
    }

    /**
     * @Route("/calendar", name="calendar", methods={"GET"})
     *  
     */
    public function calendar(): Response
    {
        return $this->render('wild/calendar.html.twig');
    }
}

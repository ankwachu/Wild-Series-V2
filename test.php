<?php
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
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
    */

    public function index() :Response
    {
        return $this->render ('wild/index.html.twig', [
            'website' => 'Wild Séries V2',
        ]);
    }

    /**
    * @Route("/wild/show/{slug}",
    *     requirements={"slug"="[a-z0-9\-]+"},
     *    methods={"GET"},
     *     defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
    *     name="wild_show"
        * )
    */
    public function show($slug) :Response
    {
        $slug = explode("-", $slug);
        $sentence = implode(" ", $slug);
        $slug = ucwords($sentence);

        return $this->render('wild/show.html.twig', [
            'slug' => $slug
        ]);
    }
}
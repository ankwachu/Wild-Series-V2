<?php

namespace App\Controller;

use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(
                array(), 
                array('name' => 'ASC')
              );

        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * Getting shows by category with a formatted slug
     *
     * @param string $categoryName
     * @Route("/category/{categoryName<^[a-z0-9-]+$>}", defaults={"categoryName" = null}, name="category_name")
     * @return Response
     */

    public function showByCategory(string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this->createNotFoundException('No category found with program.');
        }
        $categoryName = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($categoryName)), "-")
        );

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $categoryName . ' category, found in program\'s table.'
            );
        }

        return $this->render('category/category.html.twig', [
            'category' => $category,
            'categoryName'  => $categoryName,
            'programs' => $program,
        ]);
    }
}
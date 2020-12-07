<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Show all rows from Category's entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig',
            ['categories' => $categories]
        );
    }
    /**
     * The controller for the category add form
     *
     * @Route("/new", name ="new")
     * @return Response
     */
    public function new(Request $request) : Response
    {
        //create Category Object
        $category = new Category();
        //Create the form
        $form = $this->createForm(CategoryType::class, $category);
        //Get data from HTTP request
        $form->handleRequest($request);
        //Was the form submitted?
        if($form->isSubmitted() && $form->isValid()) {
            //get entity manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist object(Category)
            $entityManager->persist($category);
            //Flush
            $entityManager->flush();
            //Redirect to list
            return $this->redirectToRoute('category_index');
        }
        //Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    /**
     * Getting a category by name
     *
     *@Route("/{categoryName}", name="show")
     *@return Response
     */

    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name' => $categoryName]);

        if(!$category)
            throw new NotFoundHttpException('Category not found');

        $programs = $this ->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category'=> $category], ['id' => 'desc']); // enlevé limit:3 pour Q10

        return $this->render('category/show.html.twig', [
            'programs' => $programs
        ]);
    }

}
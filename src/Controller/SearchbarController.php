<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GifRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\QueryBuilder;

class SearchbarController extends AbstractController
{

    private GifRepository $gifRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(GifRepository $gifRepository, CategoryRepository $categoryRepository)
    {
        $this->gifRepository = $gifRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/searchbar", name="searchbar.index")
     */
    public function index(Request $request, GifRepository $gifRepository, CategoryRepository $categoryRepository)
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        //dump($request->request->get('research'));
        $value = $request->request->get('research');


        //$donnees = $gifRepository->findAll();
        //$research = $searchForm->getData();

        $categories = $categoryRepository->search($value);

        //dump($categories);


        return $this->render('searchbar/index.html.twig',
            [
                'categories' => $categories,
            ]
          );
    }
}

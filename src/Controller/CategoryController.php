<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/category/{categorySlug}", name="category.index")
     */
    public function index(string $categorySlug):Response
    {
        $category = $this->categoryRepository->findOneBy(
            ['slug' => $categorySlug]
        );

        $subCategories = $this->categoryRepository
            ->getSubCategoryByMainCategorySlug($categorySlug)
            ->getResult();


        return $this->render('category/index.html.twig', [
                'category' => $category,
                'subcategories' => $subCategories,
        ]);
    }


    /**
     * @Route("/category/{categorySlug}/{subcategorySlug}", name="category.subcategory")
     */
    public function subcategory(string $categorySlug, string $subcategorySlug):Response
    {
        $subCategory = $this->categoryRepository->findOneBy([
            'slug' => $subcategorySlug
            ]);

        return $this->render('category/subcategory.html.twig', [
                'subCategory' => $subCategory
            ]);
    }


}

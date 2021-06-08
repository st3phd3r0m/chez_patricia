<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Representation\Categories;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;


class CategoryController extends AbstractFOSRestController
{
    private const MAX_PER_PAGE = 10;

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Get(
     *      path = "/categories",
     *      name = "app_categories_list"
     * )
     * @QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page number."
     * )
     * @QueryParam(
     *     name="h_order",
     *     requirements="\d+",
     *     default="0",
     *     description="Category order."
     * )
     * @View
     * @return Category[]|Categories
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $h_order = $paramFetcher->get('h_order');
        $numberOfPages = $this->categoryRepository->getNumberOfPages(self::MAX_PER_PAGE, $h_order);
        if($page<1 || $page > $numberOfPages){
            // todo : throw an exception
            return new Categories([], $page, $numberOfPages, self::MAX_PER_PAGE);
        }

        $brands = $this->categoryRepository->getPage(self::MAX_PER_PAGE, ($page-1) * self::MAX_PER_PAGE, $h_order);
        return new Categories($brands, $page, $numberOfPages, self::MAX_PER_PAGE);
    }

    /**
     * @Get(
     *      path = "/categories/{slug}",
     *      name = "app_categories_show",
     *      requirements = {"slug"="\w+"}
     * )
     * @View
     */
    public function showAction(Category $category): Category
    {
        return $category;
    }
}
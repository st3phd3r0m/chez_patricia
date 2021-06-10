<?php

namespace App\Controller\Api;

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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Areas;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/")
 * @OA\Tag(name="Categories")
 */
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
     *      path = "categories",
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
     * @OA\Response(
     *     response=200,
     *     description="Get the list of all categories.",
     *     @Model(type=Category::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     * @return Category[]|Categories
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $h_order = $paramFetcher->get('h_order');
        $numberOfPages = $this->categoryRepository->getNumberOfPages(self::MAX_PER_PAGE, $h_order);
        if($page<1 || $page > $numberOfPages){
            throw new NotFoundResourceException("No ressource here", 404);
        }

        $brands = $this->categoryRepository->getPage(self::MAX_PER_PAGE, ($page-1) * self::MAX_PER_PAGE, $h_order);
        return new Categories($brands, $page, $numberOfPages, self::MAX_PER_PAGE);
    }

    /**
     * @Get(
     *      path = "categories/{slug}",
     *      name = "app_categories_show",
     *      requirements = {"slug"="[a-z0-9-]+"}
     * )
     * @View
     * @OA\Response(
     *     response=200,
     *     description="Return one category",
     *     @Model(type=Category::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     */
    public function showAction(Category $category): Category
    {
        return $category;
    }
}
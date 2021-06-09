<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Representation\Products;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Areas;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Products")
 */
class ProductController extends AbstractFOSRestController
{
    private const MAX_PER_PAGE = 10;

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Get(
     *      path = "/products",
     *      name = "app_products_list"
     * )
     * @QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page number."
     * )
     * @View
     * @OA\Response(
     *     response=200,
     *     description="Get the list of all products.",
     *     @Model(type=Product::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     * @return Product[]|Products
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $numberOfPages = $this->productRepository->getNumberOfPages(self::MAX_PER_PAGE);
        if($page<1 || $page > $numberOfPages){
            throw new NotFoundHttpException("No ressource here", null, 404);
        }

        $products = $this->productRepository->getPage(self::MAX_PER_PAGE, ($page-1) * self::MAX_PER_PAGE);
        return new Products($products, $page, $numberOfPages, self::MAX_PER_PAGE);
    }

    /**
     * @Get(
     *      path = "/products/{slug}",
     *      name = "app_products_show",
     *      requirements = {"slug"="[a-z0-9-]+"}
     * )
     * @View
     * @OA\Response(
     *     response=200,
     *     description="Return one product.",
     *     @Model(type=Product::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     */
    public function showAction(Product $product): Product
    {
        return $product;
    }
}
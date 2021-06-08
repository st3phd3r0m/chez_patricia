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
     * @return Product[]|Products
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $numberOfPages = $this->productRepository->getNumberOfPages(self::MAX_PER_PAGE);
        if($page<1 || $page > $numberOfPages){
            // todo : throw an exception
            return new Products([], $page, $numberOfPages, self::MAX_PER_PAGE);
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
     */
    public function showAction(Product $product): Product
    {
        return $product;
    }
}
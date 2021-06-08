<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;


class ProductController extends AbstractFOSRestController
{
    /**
     * @Get(
     *      path = "/products",
     *      name = "app_products_list"
     * )
     * @View
     * @return Product[]
     */
    public function listAction(ProductRepository $productRepository)
    {
        return $productRepository->findAll();
    }

    /**
     * @Get(
     *      path = "/products/{id}",
     *      name = "app_products_show",
     *      requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Product $product): Product
    {
        return $product;
    }
}
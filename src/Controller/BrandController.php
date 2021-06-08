<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Representation\Brands;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;

class BrandController extends AbstractFOSRestController
{
    private const MAX_PER_PAGE = 10;

    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    /**
     * @Get(
     *      path = "/brands",
     *      name = "app_brands_list"
     * )
     * @QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page number."
     * )
     * @View
     * @return Brand[]|Brands
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $numberOfPages = $this->brandRepository->getNumberOfPages(self::MAX_PER_PAGE);
        $page = $paramFetcher->get('page');
        if($page<1 || $page > $numberOfPages){
            // todo : throw an exception
            return new Brands([], $page, $numberOfPages, self::MAX_PER_PAGE);
        }

        $brands = $this->brandRepository->getPage(self::MAX_PER_PAGE, ($page-1) * self::MAX_PER_PAGE);
        return new Brands($brands, $page, $numberOfPages, self::MAX_PER_PAGE);
    }

    /**
     * @Get(
     *      path = "/brands/{id}",
     *      name = "app_brands_show",
     *      requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Brand $brand): Brand
    {
        return $brand;
    }
}
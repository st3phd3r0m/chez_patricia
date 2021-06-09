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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Areas;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Brands")
 */
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
     * @OA\Response(
     *     response=200,
     *     description="Get the list of all brands.",
     *     @Model(type=Brand::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     * @return Brand[]|Brands
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $numberOfPages = $this->brandRepository->getNumberOfPages(self::MAX_PER_PAGE);
        $page = $paramFetcher->get('page');
        if($page<1 || $page > $numberOfPages){
            throw new NotFoundHttpException("No ressource here", null, 404);
        }

        $brands = $this->brandRepository->getPage(self::MAX_PER_PAGE, ($page-1) * self::MAX_PER_PAGE);
        return new Brands($brands, $page, $numberOfPages, self::MAX_PER_PAGE);
    }

    /**
     * @Get(
     *      path = "/brands/{slug}",
     *      name = "app_brands_show",
     *      requirements = {"slug"="[a-z0-9-]+"}
     * )
     * @View
     * @OA\Response(
     *     response=200,
     *     description="Return one brand.",
     *     @Model(type=Brand::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     */
    public function showAction(Brand $brand): Brand
    {
        return $brand;
    }
}

<?php

namespace App\Controller;

use App\Entity\Image;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;


class ImageController extends AbstractFOSRestController
{

    /**
     * @Get(
     *      path = "/images/{id}",
     *      name = "app_images_show",
     *      requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Image $image): Image
    {
        return $image;
    }
}
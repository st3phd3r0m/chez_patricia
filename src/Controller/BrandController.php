<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;


class BrandController extends AbstractFOSRestController
{

    // public function index(BrandRepository $brandRepository): Response
    // {
    //     return $this->render('brand/index.html.twig', [
    //         'brands' => $brandRepository->findAll(),0
    //     ]);
    // }


    // public function new(Request $request): Response
    // {
    //     $brand = new Brand();
    //     $form = $this->createForm(BrandType::class, $brand);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($brand);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('brand_index');
    //     }

    //     return $this->render('brand/new.html.twig', [
    //         'brand' => $brand,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Get(
     *      path = "/brands",
     *      name = "app_brands_list"
     * )
     * @View
     *
     * @return Brand[]
     */
    public function listAction(BrandRepository $brandRepository)
    {
        // dd( $brandRepository->findAll() );
        return $brandRepository->findAll();
    }

    /**
     * @Get(
     *      path = "/brands/{id}",
     *      name = "app_brands_show",
     *      requirements = {"id"="\d+"}
     * )
     * @View
     *
     */
    public function showAction(Brand $brand): Brand
    {
        return $brand;
    }


    // public function edit(Request $request, Brand $brand): Response
    // {
    //     $form = $this->createForm(BrandType::class, $brand);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('brand_index');
    //     }

    //     return $this->render('brand/edit.html.twig', [
    //         'brand' => $brand,
    //         'form' => $form->createView(),
    //     ]);
    // }


    // public function delete(Request $request, Brand $brand): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($brand);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('brand_index');
    // }
}

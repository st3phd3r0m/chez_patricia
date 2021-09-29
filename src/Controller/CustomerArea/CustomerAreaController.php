<?php

namespace App\Controller\CustomerArea;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/customer")
 */
class CustomerAreaController extends AbstractController
{
    /**
     * @Route("", name="customerIndex")
     */
    public function index(): Response
    {


        return $this->render('customerArea/dashboard.html.twig');
    }

}
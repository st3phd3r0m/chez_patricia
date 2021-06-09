<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use App\Representation\Comments;
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
 * @OA\Tag(name="comments")
 */
class CommentController extends AbstractFOSRestController
{
    private const MAX_PER_PAGE = 10;

    private CommentRepository $commentRepository;
    private ProductRepository $productRepository;

    public function __construct(CommentRepository $commentRepository, ProductRepository $productRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Get(
     *      path = "/comments",
     *      name = "app_comments_list"
     * )
     * @QueryParam(
     *     name="product",
     *     requirements="[a-z0-9-]+",
     *     description="Product id."
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
     *     description="Get the list of all comments.",
     *     @Model(type=Comment::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     * @return Comment[]|Comments
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $product_slug = $paramFetcher->get('product');
        $product_id = $this->productRepository->getProductId($product_slug);

        if(null == $product_id){
            throw new NotFoundHttpException("No ressource here", null, 404);
        }

        $numberOfPages = $this->commentRepository->getNumberOfPages(self::MAX_PER_PAGE, $product_id);

        if($page<1 || $page > $numberOfPages){
            throw new NotFoundHttpException("No ressource here", null, 404);
        }

        $comments = $this->commentRepository->getPage(self::MAX_PER_PAGE, ($page-1) * self::MAX_PER_PAGE, $product_id);
        
        return new Comments($comments, $page, $numberOfPages, self::MAX_PER_PAGE);
    }

    /**
     * @Get(
     *      path = "/comments/{id}",
     *      name = "app_comments_show",
     *      requirements = {"id"="\d+"}
     * )
     * @View
     * @OA\Response(
     *     response=200,
     *     description="Return one comment.",
     *     @Model(type=Comment::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returned when not found."
     * )
     */
    public function showAction(Comment $comment): Comment
    {
        return $comment;
    }
}
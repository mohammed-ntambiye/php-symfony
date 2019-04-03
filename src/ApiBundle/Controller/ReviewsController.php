<?php

namespace ApiBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;


class ReviewsController extends FOSRestController
{
    public function getReviewsAction($isbn)
    {
        $bookService = $this->container->get('book_service');
        $bookReviews = $bookService->getReviewsForBook($isbn, 10, 0);

        if (!$bookReviews) {
            $view = $this->view(["error" => "No reviews found."], 404);
        } else {
            $view = $this->view($bookReviews);
        }
        return $this->handleView($view);
    }
}

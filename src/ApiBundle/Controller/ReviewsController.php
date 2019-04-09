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

    public function getReviewAction($isbn, $reviewId)
    {
        $bookService = $this->container->get('book_service');
        $bookReview = $bookService->getReviewForBook($isbn, $reviewId);

        if (!$bookReview) {
            $view = $this->view(["error" => "Review not found."], 404);
        } else {
            $view = $this->view($bookReview);
        }
        return $this->handleView($view);
    }

    public function postReviewAction($isbn, Request $request)
    {
        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }

        $bookService = $this->container->get('book_service');

        $fields = $request->request->all();

        $em = $this->container->get('doctrine.orm.entity_manager');

        $token = str_replace("Bearer ","", $request->server->getHeaders()["AUTHORIZATION"]);
        $user = $em->getRepository(AccessToken::class)->findOneBy([
            "token" => $token
        ]);

        if (isset($fields["rating"]) && isset($fields["review"]) && $this->isValidRating($fields["rating"])) {
            $bookReview = $bookService->createBookReview($isbn, $fields, $user->getUser()->getId());

            if (!$bookReview) {
                return $this->handleView($this->view(["error" => "An error occurred creating the review."], 500));
            }

            return $this->handleView($this->view($bookReview, 201)
                ->setLocation(
                    $this->generateUrl('api_book_review_get_book_review',
                        ['isbn' => $isbn, 'reviewId' => $bookReview->getId()]
                    )
                )
            );
        } else {
            return $this->handleView($this->view([ "error" => "Required fields are missing or invalid." ], 400));
        }
    }

    private function isValidRating($rating) {
        return filter_var($rating, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1, 'max_range' => 5 ]]);
    }
}

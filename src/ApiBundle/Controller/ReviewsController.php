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

        $em = $this->getDoctrine()->getManager();

        $token = str_replace("Bearer ","", $request->server->getHeaders()["AUTHORIZATION"]);
        $user = $em->getRepository("ApiBundle:AccessToken")->findOneBy([
            "token" => $token
        ]);

        if (isset($fields["rating"]) && isset($fields["fullReview"]) && isset($fields["summaryReview"])) {
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

    public function putReviewAction($isbn, $reviewId, Request $request)
    {
        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }

        $bookService = $this->container->get('book_service');
        if (!$bookService->getReviewForBook($isbn, $reviewId)) {
            return $this->handleView($this->view([ "error" => "Review not found."], 404));
        }

        $fields = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $token = str_replace("Bearer ","", $request->server->getHeaders()["AUTHORIZATION"]);
        $user = $em->getRepository("ApiBundle:AccessToken")->findOneBy([
            "token" => $token
        ]);


        if (isset($fields["fullReview"]) || isset($fields["rating"]) || isset($fields["summaryReview"])) {

            if (isset($fields["rating"])) {
                if (!$this->isValidRating($fields["rating"])) {
                    return $this->handleView($this->view([ "error" => "Must provide at least one field and all fields must be valid." ], 400));
                }
            }

            $result = $bookService->updateReviewForBook($isbn, $reviewId, $fields, $user->getUser()->getId());

            if (!$result) {
                return $this->handleView($this->view([ "error" => "You do not have permission to update this review." ], 403));
            }

            return $this->handleView($this->view(null, 204)
                ->setLocation(
                    $this->generateUrl('api_book_review_get_book_review',
                        ['isbn' => $isbn, 'reviewId' => $result->getId()]
                    )
                )
            );
        } else {
            return $this->handleView($this->view([ "error" => "Must provide at least one field and all fields must be valid." ], 400));
        }
    }


    public function deleteReviewAction($isbn, $reviewId, Request $request)
    {
        $bookService = $this->container->get('book_service');

        if (!$bookService->getReviewForBook($isbn, $reviewId)) {
            return $this->handleView($this->view([ "error" => "Review not found."], 404));
        }

        $em = $this->getDoctrine()->getManager();

        $token = str_replace("Bearer ","", $request->server->getHeaders()["AUTHORIZATION"]);
        $user = $em->getRepository("ApiBundle:AccessToken")->findOneBy([
            "token" => $token
        ]);

        $bookReview = $bookService->deleteReviewForBook($reviewId, $user->getUser()->getId());

        if (!$bookReview) {
            $view = $this->view(["error" => "You do not have permission to delete this review."], 403);
        } else {
            $view = $this->view(null, 204);
        }
        return $this->handleView($view);
    }



    private function isValidRating($rating) {
        return filter_var($rating, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1, 'max_range' => 5 ]]);
    }
}

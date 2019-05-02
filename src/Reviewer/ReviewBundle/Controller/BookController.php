<?php

namespace Reviewer\ReviewBundle\Controller;

use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Entity\Book;
use Reviewer\ReviewBundle\Service\BookService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
Use Sentiment\Analyzer;

class BookController extends Controller
{
    public function viewBookAction($isbn, Request $request)
    {
        /** @var BookService $bookService */
        $bookService = $this->get('book_service');
        $book = $bookService->getBookByIsbn($isbn);
        $bookReviews = $bookService->getReviewsByIsbn($isbn);
        $additionalDetails = $bookService->fetchBookDetailsByIsbn($isbn);
        $criticalReviews = $bookService->fetchCriticReviews($book->getTitle());
        $analysedReview = array();
        foreach ($bookReviews as $review) {
            $results = $bookService->textAnalyzer($review->getFullReview());
            array_push($analysedReview, [
                "Analysis" => $results,
                "Review" => $review
            ]);
        }
        $paginate = $this->get('knp_paginator');
        $pagination = $paginate->paginate(
            $analysedReview,
            $request->query->getInt('page', 1),
            3
        );
        
        $user = ($this->getUser() != null ? $this->getUser()->getUsername() : 'guest');
        $viewModel = [
            'book' => $book,
            'pagination' => $pagination,
            'criticReviews'=>$criticalReviews,
            'currentUser' => $user
        ];

        if ($additionalDetails) {
            $viewModel["publisher"] = $additionalDetails["publisher"];
            $viewModel["publish_date"] = $additionalDetails["publish_date"];
            $viewModel["author"] = $additionalDetails["author"];
            $viewModel["synopsis"] = $additionalDetails["synopsis"];
        }


        if (isset($book)) {
            return $this->render('ReviewerReviewBundle:Book:view.html.twig',$viewModel);
        } else {
            return $this->render('ReviewerReviewBundle:ErrorPages:error.html.twig', [
                'message' => 'This book does not exist'
            ]);
        }
    }

    public function createBookAction(Request $request)
    {
        $book = new Book();
        /** @var BookService $bookService */
        $em = $this->getDoctrine()->getManager();
        $bookService = $this->container->get('book_service');
        $form = $this->createForm(BookType::class, $book, [
            'action' => $request->getUri(), 'book_service' => $bookService
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $book->setGenreId($bookService->getGenreById($book->getGenreId()));

            $bookCheck = $bookService->getBookByIsbn($book->getIsbn());
            if ($bookCheck != null) {
                return $this->redirect($this->generateUrl('book_view', ['isbn' => $bookCheck->getIsbn()]));
            } else {
                $image = $book->getCoverImage();
                $filename = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('book_covers'),
                    $filename
                );

                $book->setApproval('0');
                $book->setCoverImage($filename);
                $book->setTimestamp(new \DateTime());
                $em->persist($book);
                $em->flush();
                return $this->redirect($this->generateUrl('book_view', ['isbn' => $book->getIsbn()]));
            }
        }
        return $this->render('ReviewerReviewBundle:Book:create.html.twig',
            ['form' => $form->createView()]);
    }

    public function viewBooksByGenreAction($genreId, Request $request)
    {
        $bookService = $this->container->get('book_service');
        $book = $bookService->getBookByGenre($genreId);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $book,
            $request->query->getInt('page', 1),
            5
        );

        if (isset($book)) {
            return $this->render('ReviewerReviewBundle:Book:booksByGenre.html.twig',
                ['books' => $book,
                    'pagination' => $pagination
                ]);
        } else {
            return $this->render('ReviewerReviewBundle:ErrorPages:error.html.twig', [
                'message' => 'This book does not exist'
            ]);
        }
    }

    /**
     * @param $isbn
     *
     * @return array|null
     */
    public function lookupBookDetailsByIsbn($isbn)
    {
        try {
            $response = $this->googleBooksApi->get('volumes?q=isbn:' . $isbn . '&key=AIzaSyD-f3FZyjImM9ZSVStNcwp9m18cqO3PnGU');

            if ($response->getStatusCode() == 200) {
                $match = json_decode((string)$response->getBody(), true);
                if ($match["totalItems"] == 1) {
                    $fullBook = $match["items"][0]["volumeInfo"];
                    return $this->sanitizeBookFields($isbn, $fullBook);
                }
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    private function sanitizeBookFields($isbn, $fullBook)
    {
        return [
            "isbn" => $isbn,
            "title" => $fullBook["title"],
            "publish_date" => array_key_exists("publishedDate", $fullBook) ? $fullBook["publishedDate"] : new DateTime(),
            "publisher" => array_key_exists("publisher", $fullBook) ? $fullBook["publisher"] : "Unknown",
            "author" => array_key_exists("authors", $fullBook) ? $fullBook["authors"][0] : "Unknown",
            "synopsis" => array_key_exists("description", $fullBook) ? $fullBook["description"] : "No description.",
            "cover_image" => array_key_exists("imageLinks", $fullBook) ? $fullBook["imageLinks"]["thumbnail"] : 'http://covers.openlibrary.org/b/isbn/' . $isbn . '-L.jpg',
        ];
    }

}

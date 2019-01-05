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
    public function viewBookAction($id, Request $request)
    {
        $bookService = $this->get('book_service');
        $book = $bookService->getBookById($id);
        $bookReviews = $bookService->getReviewsByBookId($id);
        $analysedReview = array();

        foreach ($bookReviews as $review) {
            $results = $bookService->textAnalyzer($review->getFullReview());
            array_push($analysedReview, [
                "Analysis" => $results[0],
                "Review" => $review
            ]);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $analysedReview,
            $request->query->getInt('page', 1),
            3
        );
        $user = ($this->getUser()? $this->getUser()->getUsername() :'guest');
        if (isset($book)) {
            return $this->render('ReviewerReviewBundle:Book:view.html.twig',
                ['book' => $book,
                    'pagination' => $pagination,
                    'currentUser' => $user,
                ]);
        } else {
            return $this->render('ReviewerReviewBundle:ErrorPages:error.html.twig', [
                'message' => 'This book does not exist'
            ]);
        }
    }

    public function createBookAction(Request $request)
    {
        $book = new Book();
        $em = $this->getDoctrine()->getManager();
        $bookService = $this->container->get('book_service');
        $form = $this->createForm(BookType::class, $book, [
            'action' => $request->getUri(), 'book_service' => $bookService
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $book->setGenreId($bookService->getGenreById($book->getGenreId()));

            $bookCheck = $bookService->getBookIdByIsbn($book->getIsbn());
            if ($bookCheck != null) {
                return $this->redirect($this->generateUrl('book_view', ['id' => $bookCheck->getId()]));
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
                return $this->redirect($this->generateUrl('book_view', ['id' => $book->getId()]));
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
            1
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

}

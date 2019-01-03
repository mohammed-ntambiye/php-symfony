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

class BookReviewController extends Controller
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
        $user = $this->getUser()->getUsername();
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
        $bookService = $this->container->get('book_service');
        $book = new Book();
        $form = $this->createForm(BookType::class, $book, [
            'action' => $request->getUri(), 'book_service' => $bookService
        ]);

        $form->handleRequest($request);
        // validates the form
        if ($form->isValid()) {
            $book->setGenreId($bookService->getGenreById($book->getGenreId()));
            $bookCheck = $bookService->getBookIdByIsbn($book->getIsbn());
            if ($bookCheck != null) {
                return $this->redirect($this->generateUrl('book_view', ['id' => $bookCheck->getId()]));
            } else {

                /** @var @Vich\Uploadable $image */
                $image = $book->getCoverImage();
                $filename = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('book_covers'),
                    $filename
                );
                $book->setApproval('0');
                $book->setCoverImage($filename);
                $book->setTimestamp(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($book);
                $em->flush();
                return $this->redirect($this->generateUrl('book_view', ['id' => $book->getId()]));
            }

        }
        return $this->render('ReviewerReviewBundle:Book:create.html.twig',
            ['form' => $form->createView()]);
    }

    public function viewReviewAction($id)
    {
        $bookService = $this->container->get('book_service');
        $review = $bookService->getReviewById($id);
        if (isset($review)) {
            return $this->render('ReviewerReviewBundle:BookReview:view.html.twig',
                ['review' => $review]);
        } else {
            return $this->render('ReviewerReviewBundle:ErrorPages:error.html.twig', [
                'message' => 'This review does not exist'
            ]);
        }
    }

    public function createReviewAction($isbn, Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review, [
            'action' => $request->getUri()
        ]);

        $bookService = $this->container->get('book_service');
        $form->handleRequest($request);
        $bookId = $bookService->getBookIdByIsbn($isbn);
        if ($request->isMethod('post')) {
            if (!isset($bookId)) {
                $form->get('bookId')->addError(new FormError('Invalid isbn please try again or use the search'));
            }
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $review->setBookId($bookId);
            $review->setTimestamp(new \DateTime());
            $review->setAuthor($this->getUser());
            $em->persist($review);
            $em->flush();
            return $this->redirect($this->generateUrl('reviewer_review_homepage'));
        }
        if ($request->headers->get('referer') != null && $bookId->getApproval()!= false) {
            return $this->render('ReviewerReviewBundle:BookReview:create.html.twig',
                ['form' => $form->createView()]);
        }
        return $this->redirect($this->generateUrl('reviewer_review_homepage'));
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

    public function editReviewAction($id, $isbn, Request $request)
    {
        $bookService = $this->container->get('book_service');
        $em = $this->getDoctrine()->getManager();
        $bookReview = $bookService->getReviewById($id);
        if ($bookReview->isAuthor($this->getUser())) {
            $form = $this->createForm(ReviewType::class, $bookReview, [
                'action' => $request->getUri()
            ]);
        } else {
            return $this->redirect($this->generateUrl('reviewer_review_homepage'));
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form->handleRequest($request);
            $bookReview->setTimestamp(new \DateTime());
            $bookReview->setAuthor($this->getUser());
            $book = $bookService->updateReview($bookReview, $isbn);
            return $this->redirect($this->generateUrl('book_view',
                ['id' => $book]));
        }

        return $this->render('ReviewerReviewBundle:BookReview:edit.html.twig',
            ['form' => $form->createView(),
                'bookReview' => $bookReview]);
    }


    public function deleteReviewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bookReview = $em->getRepository('ReviewerReviewBundle:Review')->find($id);
        $em->remove($bookReview);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('reviewer_review_homepage'));

    }

}

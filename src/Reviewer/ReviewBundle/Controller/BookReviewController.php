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


class BookReviewController extends Controller
{

    public function viewBookAction($id)
    {

        $bookService = $this->container->get('book_service');
        $book = $bookService->getBookById($id);
        if (isset($book)) {
            return $this->render('ReviewerReviewBundle:Book:view.html.twig',
                ['book' => $book]);
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

        // If the request is post it will populate the form
        $form->handleRequest($request);
        // validates the form
        if ($form->isValid()) {

            $book->setGenreId($bookService->getGenreById($book->getGenreId()));
            /** @var @Vich\Uploadable $image */
            $image = $book->getCoverImage();

            $filename = md5(uniqid()) . '.' . $image->guessExtension();

            $image->move(
                $this->getParameter('book_covers'),
                $filename
            );

            $book->setCoverImage($filename);
            $book->setTimestamp(new \DateTime());
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            // commit all changes
            $em->flush();

            return $this->redirect($this->generateUrl('book_view', ['id' => $book->getId()]));
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


    public function createReviewAction(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review, [
            'action' => $request->getUri()
        ]);
        $bookService = $this->container->get('book_service');

        $form->handleRequest($request);

        if ($request->isMethod('post')) {
            $bookId = $bookService->getBookIdByIsbn($review->getBookId());
            if (!isset($bookId)) {
                $form->get('bookId')->addError(new FormError('Invalid isbn please try again or use the search'));
            }
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $review->setBookId($bookId);
            $review->setAuthor($this->getUser());
            $review->setTimestamp(new \DateTime());
            $em->persist($review);
            $em->flush();
            return $this->redirect($this->generateUrl('review_view', ['id' => $review->getId()]));
        }
        return $this->render('ReviewerReviewBundle:BookReview:create.html.twig',
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

//    public function editAction($id, Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')->find($id);
//        $form = $this->createForm(BookReviewType::class, $bookReview, [
//            'action' => $request->getUri()
//        ]);
//        $form->handleRequest($request);
//        if ($form->isValid()) {
//            $em->flush();
//            return $this->redirect($this->generateUrl('view',
//                ['id' => $bookReview->getId()]));
//        }
//        return $this->render('ReviewerReviewBundle:BookReview:edit.html.twig',
//            ['form' => $form->createView(),
//                'bookReview' => $bookReview]);
//    }
//
//
//    public function deleteAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')->find($id);
//        $em->remove($bookReview);
//        $em->flush();
//
//        return $this->redirect(
//            $this->generateUrl('reviewer_review_homepage'));
//
//    }

}

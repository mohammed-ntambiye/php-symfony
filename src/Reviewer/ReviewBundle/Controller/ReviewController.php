<?php

namespace Reviewer\ReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Reviewer\ReviewBundle\Entity\Book;
use Reviewer\ReviewBundle\Service\BookService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
Use Sentiment\Analyzer;
class ReviewController extends Controller
{
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
        $em = $this->getDoctrine()->getManager();
        $bookService = $this->container->get('book_service');

        $form = $this->createForm(ReviewType::class, $review, [
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);
        $bookId = $bookService->getBookByIsbn($isbn);

        if ($request->isMethod('post')) {
            if (!isset($bookId)) {
                $form->get('bookId')->addError(new FormError('Invalid isbn please try again or use the search'));
            }
        }

        if ($form->isValid()) {
            $review->setBookId($bookId);
            $review->setReports(0);
            $review->setTimestamp(new \DateTime());
            $review->setAuthor($this->getUser());
            $em->persist($review);
            $em->flush();
            return $this->redirect($this->generateUrl('reviewer_review_homepage'));
        }

        if ($request->headers->get('referer') != null && $bookId->getApproval() != false) {
            return $this->render('ReviewerReviewBundle:BookReview:create.html.twig',
                ['form' => $form->createView()]);
        }
        return $this->redirect($this->generateUrl('reviewer_review_homepage'));
    }

    public function editReviewAction($id, $isbn, Request $request)
    {
        $bookService = $this->container->get('book_service');
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

    public function reportAction($id)
    {
        if ($this->getUser() != null) {
            $bookService = $this->container->get('book_service');
            $bookService->reportReview($id);
            return $this->render('ReviewerReviewBundle:BookReview:reportConfirmation.html.twig');
        }
        return $this->redirect($this->generateUrl('reviewer_review_homepage'));
    }
}

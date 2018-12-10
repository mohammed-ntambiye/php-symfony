<?php

namespace Reviewer\ReviewBundle\Controller;

use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

class BookReviewController extends Controller
{

    public function viewBookAction($id)
    {
        var_dump("hello");
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();
        // Use the entity manager to retrieve the Entry entity for the id
        // that has been passed
        $book = $em->getRepository('ReviewerReviewBundle:Book')->find($id);
        // Pass the entry entity to the view for displaying
        return $this->render('ReviewerReviewBundle:Book:view.html.twig',
            ['book' => $book]);
    }

    public function createBookAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book, [
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $form->handleRequest($request);
        // validates the form
        if ($form->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            // commit all changes
            $em->flush();
            return $this->redirect($this->generateUrl('view',
                ['id' => $book->getId()]));
        }
        return $this->render('ReviewerReviewBundle:Book:create.html.twig',
            ['form' => $form->createView()]);
    }


    public function viewReviewAction($id)
    {
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();
        // Use the entity manager to retrieve the Entry entity for the id
        // that has been passed
        $book = $em->getRepository('ReviewerReviewBundle:Book')->find($id);
        // Pass the entry entity to the view for displaying
        return $this->render('ReviewerReviewBundle:BookReview:view.html.twig',
            ['book' => $book]);
    }



    public function createReviewAction(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review, [
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $form->handleRequest($request);

        // validates the form
        if ($form->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            // manually set the author to the current user
            $review->setAuthor($this->getUser());
            // manually set the timestamp to a new DateTime object
            $review->setTimestamp(new \DateTime());
            // tell the entity manager we want to persist this entity
            $em->persist($review);
            // commit all changes
            $em->flush();
            return $this->redirect($this->generateUrl('view',
                ['id' => $review->getId()]));
        }
        // Render the view from the twig file and pass the form to the view
        return $this->render('ReviewerReviewBundle:BookReview:create.html.twig',
            ['form' => $form->createView()]);
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

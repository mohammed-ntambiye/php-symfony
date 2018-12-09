<?php

namespace Reviewer\ReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Form\BookReviewType;
use Reviewer\ReviewBundle\Entity\BookReview;
use Symfony\Component\HttpFoundation\Request;
use Reviewer\ReviewBundle\Repository\BookReviewRepository;
class BookReviewController extends Controller
{

    public function viewAction($id)
    {

        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();
        // Use the entity manager to retrieve the Entry entity for the id
        // that has been passed
        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')->find($id);
        // Pass the entry entity to the view for displaying
        return $this->render('ReviewerReviewBundle:BookReview:view.html.twig',
            ['bookReview' => $bookReview]);
    }

    public function createAction(Request $request)
    {
        $reviewEntry = new BookReview();
        $form = $this->createForm(BookReviewType::class, $reviewEntry, [
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $form->handleRequest($request);

//        var_dump($reviewEntry);
        $em = $this->getDoctrine()->getManager();
        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')
            ->checkForExistingReview($reviewEntry->getTitle(),$reviewEntry->getBookAuthor());


        // validates the form
        if ($form->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            // manually set the author to the current user
            $reviewEntry->setAuthor($this->getUser());
            // manually set the timestamp to a new DateTime object
            $reviewEntry->setTimestamp(new \DateTime());
            // tell the entity manager we want to persist this entity
            $em->persist($reviewEntry);
            // commit all changes
            $em->flush();
//            return $this->redirect($this->generateUrl('view',
//                ['id' => $reviewEntry->getId()]));
        }
        // Render the view from the twig file and pass the form to the view
        return $this->render('ReviewerReviewBundle:BookReview:create.html.twig',
            ['form' => $form->createView()]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')->find($id);
        $form = $this->createForm(BookReviewType::class, $bookReview, [
            'action' => $request->getUri()
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('view',
                ['id' => $bookReview->getId()]));
        }
        return $this->render('ReviewerReviewBundle:BookReview:edit.html.twig',
            ['form' => $form->createView(),
                'bookReview' => $bookReview]);
    }


    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')->find($id);
        $em->remove($bookReview);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('reviewer_review_homepage'));

    }

}

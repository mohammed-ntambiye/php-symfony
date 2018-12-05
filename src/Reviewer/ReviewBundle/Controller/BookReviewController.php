<?php

namespace Reviewer\ReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookReviewController extends Controller
{
    public function viewAction()
    {
        return $this->render('ReviewerReviewBundle:BookReview:view.html.twig', array(
            // ...
        ));
    }

    public function createAction()
    {
        return $this->render('ReviewerReviewBundle:BookReview:create.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('ReviewerReviewBundle:BookReview:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('ReviewerReviewBundle:BookReview:delete.html.twig', array(
            // ...
        ));
    }

}

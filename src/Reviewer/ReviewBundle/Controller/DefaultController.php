<?php

namespace Reviewer\ReviewBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class DefaultController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bookReview = $em->getRepository('ReviewerReviewBundle:BookReview')
            ->getLatest(10, 0);
        return $this->render('ReviewerReviewBundle:Default:index.html.twig',
            ['bookReviews' => $bookReview]);
    }

  }


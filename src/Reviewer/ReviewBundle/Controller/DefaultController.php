<?php

namespace Reviewer\ReviewBundle\Controller;
use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Entity\Book;
use Reviewer\ReviewBundle\Service\BookService;
class DefaultController extends Controller
{

    public function indexAction()

    {  $entityManger = $this->getDoctrine()->getManager();
        $bookService = $this->container->get('book_service');
        $bookReview = $bookService ->getLatest(10, 0);

        return $this->render('ReviewerReviewBundle:Default:index.html.twig',
            ['bookReviews' => $bookReview]);
    }

  }


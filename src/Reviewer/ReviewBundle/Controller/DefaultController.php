<?php

namespace Reviewer\ReviewBundle\Controller;

use Reviewer\ReviewBundle\Entity\Review;
use Reviewer\ReviewBundle\Entity\User;
use Reviewer\ReviewBundle\Form\BookType;
use Reviewer\ReviewBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Reviewer\ReviewBundle\Entity\Book;
use Reviewer\ReviewBundle\Service\BookService;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $bookService = $this->container->get('book_service');
        $allGenres = $bookService->getAllGenres();
        $latestReviews = $bookService->getLatestReviews();
        return $this->render('ReviewerReviewBundle:Default:index.html.twig',
            ['genres' => $allGenres,
                'bookReviews' => $latestReviews
            ]
        );
    }
}


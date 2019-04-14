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

class FilteringController extends Controller
{

    public function submitSearchAction($seachQuery, Request $request)
    {
        $FilteringService = $this->container->get('filtering_service');
        $results = $FilteringService->searchBooks($seachQuery);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 4),
            4
        );

        return $this->render('ReviewerReviewBundle:Filtering:filterResults.html.twig',
            ['pagination' => $pagination]);
    }
}


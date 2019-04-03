<?php

namespace ApiBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;


class BooksController extends FOSRestController
{
    public function getBooksAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }
}

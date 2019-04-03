<?php

namespace ApiBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;


class BooksController extends FOSRestController
{
    public function getBooksAction(Request $request)
    {
        $title = $request->query->get('title');
        $author = $request->query->get('author');

        if ($title || $author) {
            $bookService = $this->container->get('book_service');

            $view = $this->view($bookService->getBooksByTitleAuthor($title, $author));
        } else {
            $view = $this->view([ "error" => "Missing author or title parameter."], 400);
        }

        return $this->handleView($view);
    }

    public function getBookAction($isbn)
    {
        $bookService = $this->container->get('book_service');

        $book = $bookService->getBookByIsbn($isbn);

        if ($book) {
            $view = $this->view($book);
        } else {
            $view = $this->view([ "error" => "No book found on Beyond the Cover with that ISBN."], 404);
        }
        return $this->handleView($view);
    }
}

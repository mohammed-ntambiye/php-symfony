<?php

namespace ApiBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class GenresController extends FOSRestController
{
    public function getGenresAction()
    {
        $bookService = $this->container->get('book_service');

        $genres = $bookService->getGenres();

        if ($genres) {
            $view = $this->view($genres, 200);
        } else {
            $view = $this->view([ "error" => "No genres found."], 404);
        }

        return $this->handleView($view);
    }

    public function getGenreAction($genreId)
    {
        $bookService = $this->container->get('book_service');

        $genre = $bookService->getGenreById($genreId);

        if ($genre) {
            $view = $this->view($genre, 200);
        } else {
            $view = $this->view([ "error" => "Genre does not exist." ], 404);
        }

        return $this->handleView($view);
    }

}
<?php

namespace Reviewer\ReviewBundle\Service;

use \Reviewer\ReviewBundle\Entity\Genre;
use \Reviewer\ReviewBundle\Entity\Book;
use \Reviewer\ReviewBundle\Entity\Review;
use Doctrine\ORM\EntityManager;

class BookService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BookService constructor.
     *
     * @param $entityManager EntityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    /**
     * Returns all Books genres
     *
     * @return array
     */
    public function getGenres()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT g.id, g.genreName FROM ReviewerReviewBundle:Genre g');

        return $query->getResult();
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    public function getBookById($id)
    {
        $em = $this->getEntityManager();

        $result = $em->getRepository(Book::class)->find($id);

        return $result;
    }


    public function getGenreById($id)
    {
        $em = $this->getEntityManager();

        $result = $em->getRepository(Genre::class)->find($id);

        return $result;
    }


    public function getAllGenres()
    {
        $em = $this->getEntityManager();

        $result = $em->getRepository(Genre::class)->findAll();

        return $result;
    }


    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }


    public function getReviewById($id)
    {
        $em = $this->getEntityManager();

        return $em->getRepository(Review::class)->find($id);
    }


    public function getLatestReviews()
    {

        $em = $this->getEntityManager();

        $reviews = $em->getRepository(Review::class)->findBy(
            ['timestamp' => 'ASC']

        );

        $result = array();

        foreach ($reviews as $review) {
            array_push($result, $this->getBookById($review->getBookId()));
        }          [timestamp => 'ASC']

        return $result;

    }


    public function getBookIdByIsbn($isbn)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Book::class)->findOneBy(
            ['isbn' => $isbn]
        );

    }


}
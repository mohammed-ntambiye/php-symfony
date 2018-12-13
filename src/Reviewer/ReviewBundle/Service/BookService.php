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



    public function getRevieWById($id)
    {
        $em = $this->getEntityManager();

        return $em->getRepository(Review::class)->find($id);
    }



    public function getLatest($limit, $offset)
    {

//        $em = $this->getEntityManager();
//        $query = $em->createQuery(
//            'SELECT rating FROM ReviewerReviewBundle:Review g');
//        return $query->getResult();

    }

    public function getBookIdByIsbn($isbn)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Book::class)->findOneBy(
            [ 'isbn' => $isbn ]
        );

    }


}
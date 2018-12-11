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

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
    }


    public function getBookById($id)
    {
        $em = $this->getEntityManager();

        return $em->getRepository(Book::class)->find($id);
    }

    public function getLatest($limit, $offset)
    {
        $queryBuilder = $this->createQueryBuilder('Review');
        $queryBuilder->orderBy('Review.timestamp', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }



}
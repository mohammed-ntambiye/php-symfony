<?php

namespace Reviewer\ReviewBundle\Service;

use phpDocumentor\Reflection\DocBlock\Tags\Author;
use \Reviewer\ReviewBundle\Entity\Genre;
use \Reviewer\ReviewBundle\Entity\Book;
use \Reviewer\ReviewBundle\Entity\Review;
use Doctrine\ORM\EntityManager;
use Reviewer\ReviewBundle\ReviewerReviewBundle;
Use Sentiment\Analyzer;

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
        $query = $em->createQuery(
            'SELECT r.timestamp, b.title, b.isbn,b.id,r.rating, b.coverImage FROM ReviewerReviewBundle:Review r
             JOIN  ReviewerReviewBundle:Book b 
             WITH r.bookId =  b.id
             ORDER BY r.timestamp DESC
            ');

        $query->setMaxResults(4);
        return $query->getResult();
    }

    public function getBookIdByReviewId($reviewId)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Review::class)->findOneBy(['id' => $reviewId]);

    }


    public function getBookIdByIsbn($isbn)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);
    }

    public function getBookByGenre($genreId)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Book::class)->findBy(
            ['genre_id' => $genreId]
        );
    }

    public function getReviewsByBookId($bookId)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Review::class)->findBy(
            ['bookId' => $bookId],
            ['timestamp' => 'DESC']
        );
    }

    public function textAnalyzer($sentence)
    {
        $analyzer = new Analyzer();
        $result = $analyzer->getSentiment($sentence);
        $max = array_keys($result, max($result));
        return $max;
    }

    public function updateReview($review,$isbn)
    {
        $em = $this->getEntityManager();
        $book= $this->getBookIdByIsbn($isbn);
        $review->setBookId($book);
        $em->persist($review);
        $em->flush();
        return $book->getId();
    }


}
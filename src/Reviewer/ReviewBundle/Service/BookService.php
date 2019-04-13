<?php

namespace Reviewer\ReviewBundle\Service;

use phpDocumentor\Reflection\DocBlock\Tags\Author;
use \Reviewer\ReviewBundle\Entity\Genre;
use \Reviewer\ReviewBundle\Entity\Book;
use \Reviewer\ReviewBundle\Entity\Review;
use Doctrine\ORM\EntityManager;
use Reviewer\ReviewBundle\Entity\User;
use Reviewer\ReviewBundle\ReviewerReviewBundle;
Use Sentiment\Analyzer;
use DateTime;
use GuzzleHttp;

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
        $this->googleBooksApi = new GuzzleHttp\Client(['base_uri' => 'https://www.googleapis.com/books/v1/']);
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->entityManager;
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
        return   $em->getRepository(Book::class)->find($id);
    }


    public function getGenreById($id)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Genre::class)->find($id);
    }


    public function getAllGenres()
    {
        $em = $this->getEntityManager();

        $result = $em->getRepository(Genre::class)->findAll();

        return $result;
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
            'SELECT r.fullReview, r.timestamp, b.title, b.isbn,b.id,r.rating, b.coverImage FROM ReviewerReviewBundle:Review r
                JOIN  ReviewerReviewBundle:Book b          
                WITH r.bookId =  b.id
                WHERE b.approval =1 AND r.reports < 10
                ORDER BY r.timestamp DESC
            ');

        $query->setMaxResults(4);
        return $query->getResult();
    }

    public function getBookByIsbn($isbn)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);
    }

    public function getBookByGenre($genreId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT b.title, b.id, b.isbn, b.coverImage FROM ReviewerReviewBundle:Book b
        WHERE b.genre_id = $genreId AND b.approval =1");
            return ($query->getResult());
    }

    public function getReviewsByBookId($bookId)
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Review::class)->findBy(
            ['bookId' => $bookId],
            ['timestamp' => 'DESC']
        );
    }

    public function getReviewsByIsbn($isbn){
        $em = $this->getEntityManager();
        $book = $em->getRepository(Book::class)->findBy(['isbn'=>$isbn]);

        return $em->getRepository(Review::class)->findBy(
            ['bookId' => $book[0]->getId()],
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

    /**
     * @param $isbn
     * @param $fields
     * @param $userId
     *
     * @return Review
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createBookReview($isbn, $fields, $userId)
    {
        $em = $this->getEntityManager();
        $reviewEntity = null;
        if (isset($userId) && isset($fields["rating"]) && isset($fields["fullReview"]) && isset($fields["summaryReview"])) {
            $reviewEntity = new Review();

            $book = $this->getBookByIsbn($isbn);
            $user = $em->getRepository(User::class)->find($userId);
            $reviewEntity->setAuthor($user);
            $reviewEntity->setTimestamp(new DateTime());
            $reviewEntity->setRating($fields["rating"]);
            $reviewEntity->setFullReview($fields["fullReview"]);
            $reviewEntity->setReports(0);
            $reviewEntity->setSummaryReview($fields["summaryReview"]);
            $reviewEntity->setBookId($book);

            $em->persist($reviewEntity);
            $em->flush($reviewEntity);
        }

        return $reviewEntity;
    }

    public function updateReview($review, $isbn)
    {
        $em = $this->getEntityManager();
        $book = $this->getBookByIsbn($isbn);
        $review->setBookId($book);
        $em->persist($review);
        $em->flush();
        return $book->getId();
    }

    public function reportReview($id){
        $em = $this->getEntityManager();
        $reportedReview = $this->getReviewById($id);
        $reportedReview->setReports($reportedReview->getReports()+1);
        $em->persist($reportedReview);
        $em->flush();
    }

    public function getReviewsForBook($isbn, $limit, $offset)
    {
        $em = $this->getEntityManager();
        $book = $this->getBookByIsbn($isbn);

        $reviews = $em->getRepository(Review::class)->findBy(
            ['bookId' => $book->getId()],
            ['timestamp' => 'ASC'],
            $limit,
            $offset
        );

        $result = array();
        foreach ($reviews as $review) {
            array_push($result, [
                "id" => $review->getId(),
                "username" => $review->getAuthor()->getUsername(),
                "rating" => $review->getRating(),
                "review" => $review->getFullReview(),
                "date" => $review->getTimestamp(),
            ]);
        }
        return $result;
    }

    public function updateReviewForBook($reviewId, $fields, $userId)
    {
        $em = $this->getEntityManager();
        $review =  $em->getRepository(Review::class)->find($reviewId);

        if ($review->getAuthor()->getId() == $userId) {
            if (isset($fields['fullReview'])) {
                $review->setFullReview($fields['fullReview']);
            }

            if (isset($fields['summaryReview'])) {
                $review->setSummaryReview($fields['summaryReview']);
            }

            if (isset($fields['rating'])) {
                $review->setRating($fields['rating']);
            }

            $review->setTimestamp(new DateTime());

            $em->persist($review);
            $em->flush();
        } else {
            $review = null;
        }

        return $review;
    }

    public function deleteReviewForBook($reviewId, $userId)
    {
        $em = $this->getEntityManager();
        $review =  $em->getRepository(Review::class)->find($reviewId);

        if ($review->getAuthor()->getId() == $userId) {
            $em->remove($review);
            $em->flush();
            return true;
        }

        return false;
    }


    public function lookupBookDetailsByIsbn($isbn)
    {
        try {
            $response = $this->googleBooksApi->get('volumes?q=isbn:' . $isbn . '&key=AIzaSyD-f3FZyjImM9ZSVStNcwp9m18cqO3PnGU');

            var_dump(json_decode((string)$response->getBody()));

            if ($response->getStatusCode() == 200) {
                $match = json_decode((string)$response->getBody(), true);

                if ($match["totalItems"] == 1) {
                    $fullBook = $match["items"][0]["volumeInfo"];

                    return $this->sanitizeBookFields($isbn, $fullBook);
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    public function getReviewForBook($isbn, $reviewId)
    {
        $em = $this->getEntityManager();
        $book = $this->getBookByIsbn($isbn);
        $review = $em->getRepository(Review::class)->findBy(
            ['id' => $reviewId, 'bookId' => $book->getId()]
        );

        sizeOf($review) ? $result = [
            "id" => $review[0]->getId(),
            "username" => $review[0]->getAuthor()->getUsername(),
            "rating" => $review[0]->getRating(),
            "review" => $review[0]->getFullReview(),
            "date" => $review[0]->getTimestamp(),
        ] : $result = [];

        return $result;
    }

    private function sanitizeBookFields($isbn, $fullBook)
    {
        return [
            "isbn" => $isbn,
            "title" => $fullBook["title"],
            "publish_date" => array_key_exists("publishedDate", $fullBook) ? $fullBook["publishedDate"] : new DateTime(),
            "publisher" => array_key_exists("publisher", $fullBook) ? $fullBook["publisher"] : "Unknown",
            "author" => array_key_exists("authors", $fullBook) ? $fullBook["authors"][0] : "Unknown",
            "synopsis" => array_key_exists("description", $fullBook) ? $fullBook["description"] : "No description.",
            "cover_image" => array_key_exists("imageLinks", $fullBook) ? $fullBook["imageLinks"]["thumbnail"] : 'http://covers.openlibrary.org/b/isbn/' . $isbn . '-L.jpg',
        ];
    }
}
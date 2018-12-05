<?php

namespace Reviewer\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookReview
 *
 * @ORM\Table(name="book_review")
 * @ORM\Entity(repositoryClass="Reviewer\ReviewBundle\Repository\BookReviewRepository")
 */
class BookReview
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="bookAuthor", type="string", length=255)
     */
    private $bookAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="summaryReviewer", type="string", length=255)
     */
    private $summaryReviewer;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="string", length=255)
     */
    private $review;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bookAuthor.
     *
     * @param string $bookAuthor
     *
     * @return BookReview
     */
    public function setBookAuthor($bookAuthor)
    {
        $this->bookAuthor = $bookAuthor;

        return $this;
    }

    /**
     * Get bookAuthor.
     *
     * @return string
     */
    public function getBookAuthor()
    {
        return $this->bookAuthor;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return BookReview
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set summaryReviewer.
     *
     * @param string $summaryReviewer
     *
     * @return BookReview
     */
    public function setSummaryReviewer($summaryReviewer)
    {
        $this->summaryReviewer = $summaryReviewer;

        return $this;
    }

    /**
     * Get summaryReviewer.
     *
     * @return string
     */
    public function getSummaryReviewer()
    {
        return $this->summaryReviewer;
    }

    /**
     * Set review.
     *
     * @param string $review
     *
     * @return BookReview
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review.
     *
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }
}

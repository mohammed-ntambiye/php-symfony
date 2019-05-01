<?php

namespace Reviewer\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * review
 * @ORM\Entity
 * @ORM\Table(name="review")
 */
class Review
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
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity="Reviewer\ReviewBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="summeryReview", type="string", length=300)
     */
    private $summeryReview;

    /**
     * @var string
     *
     * @ORM\Column(name="fullReview", type="string", length=500)
     */
    private $fullReview;

    /**
     * @ORM\Column(type="float")
     */
    private $rating;
    /**
     * @var int
     *
     * @ORM\Column(name="reports", type="integer")
     */
    private $reports;


    /**
     * @ORM\ManyToOne(targetEntity="Reviewer\ReviewBundle\Entity\Book")
     * @ORM\JoinColumn(name="bookId", referencedColumnName="id")
     */
    private $bookId;
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
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return review
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
    /**
     * Get timestamp.
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set user_id.
     *
     * @param string $user_id
     *
     * @return review
     */
    public function setAuthor($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
    /**
     * Get user_id.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->user_id;
    }
    /**
     * Set summaryReview.
     *
     * @param string $summaryReview
     *
     * @return review
     */
    public function setSummaryReview($summaryReview)
    {
        $this->summeryReview = $summaryReview;

        return $this;
    }
    /**
     * Get summaryReview.
     *
     * @return string
     */
    public function getSummaryReview()
    {
        return $this->summaryReview;
    }
    /**
     * Set rating.
     *
     * @param string $rating
     *
     * @return review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }
    /**
     * Get rating.
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }
    /**
     * Set bookId.
     *
     * @param string $bookId
     *
     * @return review
     */
    public function setBookId($bookId)
    {
        $this->bookId = $bookId;

        return $this;
    }
    /**
     * Get bookId.
     *
     * @return string
     */
    public function getBookId()
    {
        return $this->bookId;
    }
    /**
     * Set fullReview.
     *
     * @param string $fullReview
     *
     * @return Review
     */
    public function setFullReview($fullReview)
    {
        $this->fullReview = $fullReview;

        return $this;
    }
    /**
     * Get fullReview.
     *
     * @return string
     */
    public function getFullReview()
    {
        return $this->fullReview;
    }
    /**
     * Get fullReview.
     *
     * @return string
     */
    public function setId($id)
    {
        $this->id = id;

        return $this;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getBookId();
    }

    public function isAuthor(User $user = null)
    {
        return $user && $user->hasReview($this);
    }

    /**
     * Set reports.
     *
     * @param int $reports
     *
     * @return Review
     */
    public function setReports($reports)
    {
        $this->reports = $reports;

        return $this;
    }

    /**
     * Get reports.
     *
     * @return int
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Set summeryReview.
     *
     * @param string $summeryReview
     *
     * @return Review
     */
    public function setSummeryReview($summeryReview)
    {
        $this->summeryReview = $summeryReview;

        return $this;
    }

    /**
     * Get summeryReview.
     *
     * @return string
     */
    public function getSummeryReview()
    {
        return $this->summeryReview;
    }
}

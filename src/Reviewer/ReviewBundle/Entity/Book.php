<?php

namespace Reviewer\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Book
 * @ORM\Entity
 * @ORM\Table(name="book")
 * @Vich\Uploadable
 */
class Book
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
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=20, unique=true)
     * @Assert\Isbn(
     *     message = "This value is not  valid."
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $approval;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="bookDescription", type="string", length=400)
     */
    private $bookDescription;


    /**
     * @ORM\ManyToOne(targetEntity="Reviewer\ReviewBundle\Entity\Genre")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre_id;

    /**
     * @ORM\Column(type="string")
     */
    private $coverImage;

    /**
     * @Vich\UploadableField(mapping="book_covers", fileNameProperty="coverImage")
     * @var File
     */
    private $imageFile;


    /**Register
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isbn.
     *
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn.
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Book
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
     * Set coverImage.
     *
     * @param string $coverImage
     *
     * @return Book
     */
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * Get coverImage.
     *
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    /**
     * Get genreId.
     *
     * @return \Reviewer\ReviewBundle\Entity\genre|null
     */
    public function getGenreId()
    {
        return $this->genre_id;
    }

    /**
     * Set genre.
     *
     * @param mixed $genre
     */
    public function setGenreId($genre)
    {
        $this->genre_id = $genre;
    }

    /**
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return Book
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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Set bookDescription.
     *
     * @param string $bookDescription
     *
     * @return Book
     */
    public function setBookDescription($bookDescription)
    {
        $this->bookDescription = $bookDescription;

        return $this;
    }

    /**
     * Get bookDescription.
     *
     * @return string
     */
    public function getBookDescription()
    {
        return $this->bookDescription;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }


    /**
     * Set approval.
     *
     * @param bool $approval
     *
     * @return Book
     */
    public function setApproval($approval)
    {
        $this->approval = $approval;

        return $this;
    }

    /**
     * Get approval.
     *
     * @return bool
     */
    public function getApproval()
    {
        return $this->approval;
    }
}

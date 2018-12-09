<?php

namespace Reviewer\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="Reviewer\ReviewBundle\Repository\BookRepository")
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
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=12)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    /**
     * @ORM\ManyToOne(targetEntity="Reviewer\ReviewBundle\Entity\Genre")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre_id;

    /**
     * @var string
     *
     * @ORM\Column(name="coverImage", type="string", length=255)
     */
    private $coverImage;


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
     * Set genre.
     *
     * @param string $genre
     *
     * @return Book
     */
    public function setGenre_id($genre)
    {
        $this->genre_id = $genre;

        return $this;
    }

    /**
     * Get genre.
     *
     * @return string
     */
    public function getGenre_id()
    {
        return $this->genre_id;
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
     * Set genreId.
     *
     * @param \Reviewer\ReviewBundle\Entity\genre|null $genreId
     *
     * @return Book
     */
    public function setGenreId(\Reviewer\ReviewBundle\Entity\genre $genreId = null)
    {
        $this->genre_id = $genreId;

        return $this;
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
}

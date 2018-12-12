<?php

namespace Reviewer\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * genre
 * @ORM\Entity
 * @ORM\Table(name="genre")
 */
class Genre
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
     * @ORM\Column(name="genreName", type="string", length=255)
     */
    private $genreName;

    /**
     * @var string
     *
     * @ORM\Column(name="genreIcon", type="string", length=10)
     */
    private $genreIcon;



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
     * Set genreName.
     *
     * @param string $genreName
     *
     * @return genre
     */
    public function setGenreName($genreName)
    {
        $this->genreName = $genreName;

        return $this;
    }

    /**
     * Get genreName.
     *
     * @return string
     */
    public function getGenreName()
    {
        return $this->genreName;
    }

    /**
     * Set genreIcon.
     *
     * @param string $genreIcon
     *
     * @return Genre
     */
    public function setGenreIcon($genreIcon)
    {
        $this->genreIcon = $genreIcon;

        return $this;
    }

    /**
     * Get genreIcon.
     *
     * @return string
     */
    public function getGenreIcon()
    {
        return $this->genreIcon;
    }
}

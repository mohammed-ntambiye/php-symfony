<?php

namespace Reviewer\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="Reviewer\ReviewBundle\Repository\genreRepository")
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
}

<?php
/**
 * Created by PhpStorm.
 * User: arslaan
 * Date: 22/10/18
 * Time: 12:57
 */

namespace Reviewer\ReviewBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name = "Registred_users")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        $this->entries  = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Reviewer\ReviewBundle\Entity\BookReview",mappedBy="bookAuthor")
     */
    protected $entries;


    /**
     * Remove entry.
     *
     * @param \Reviewer\ReviewBundle\Entity\BookReview $entry
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */

    public function removeEntry(\Reviewer\ReviewBundle\Entity\BookReview $entry)
    {
        return $this->entries->removeElement($entry);
    }
    /**
     * Get entries.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }


    /**
     * Add entry.
     *
     * @param \Reviewer\ReviewBundle\Entity\BookReview $entry
     *
     * @return User
     */
    public function addEntry(\Reviewer\ReviewBundle\Entity\BookReview $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }
}

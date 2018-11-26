<?php
/**
 * Created by PhpStorm.
 * User: arslaan
 * Date: 22/10/18
 * Time: 12:57
 */

namespace Blogger\BlogBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ORM\Entity
 * @ORM\Table(name = "users_workshop")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Blogger\BlogBundle\Entity\Entry",mappedBy="author")
     */
    protected $entries;


    /**
     * Add entry.
     *
     * @param \Blogger\BlogBundle\Entity\Entry $entry
     *
     * @return User
     */
    public function addEntry(\Blogger\BlogBundle\Entity\Entry $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry.
     *
     * @param \Blogger\BlogBundle\Entity\Entry $entry
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEntry(\Blogger\BlogBundle\Entity\Entry $entry)
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
}

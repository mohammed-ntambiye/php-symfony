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
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Reviewer\ReviewBundle\Entity\Review", mappedBy="user_id")
     */
    protected $reviews;

    public function __construct()
    {
        parent::__construct();
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get Reviews.
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    public function hasReview(Review $review)
    {
        return $this->getReviews()->contains($review);
    }
}

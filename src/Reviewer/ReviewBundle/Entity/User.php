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

}

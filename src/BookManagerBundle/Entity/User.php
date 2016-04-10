<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 10/04/16
 * Time: 23.00
 */

namespace BookManagerBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="auto")
     */
    protected $id;

    public function __construct(){
        parent::__construct();
    }
}

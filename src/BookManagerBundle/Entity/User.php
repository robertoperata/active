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
use Rhumsaa\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    protected $id;

    public function __construct(){
        parent::__construct();
        if (empty($this->id)) {
            $this->id = Uuid::uuid4();
        }
    }
}

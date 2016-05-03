<?php


namespace BookManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 * @package BookManagerBundle\Entity
 * @ORM\Entity()
 * @ORM\Table
 */
class Order
{

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @var str
     */
    protected $id;
}

<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 10/04/16
 * Time: 23.00
 */

namespace BookManagerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="user")
     */
    private $reservations;

    /**
     * @ORM\Column(type="integer")
     */
    private $cell_number;

    public function __construct(){
        parent::__construct();
        $this->schedules = new ArrayCollection();
    }

    /**
     * Add reservation
     *
     * @param \BookManagerBundle\Entity\Reservation $reservation
     *
     * @return User
     */
    public function addReservation(\BookManagerBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \BookManagerBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\BookManagerBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Set cellNumber
     *
     * @param integer $cellNumber
     *
     * @return User
     */
    public function setCellNumber($cellNumber)
    {
        $this->cell_number = $cellNumber;

        return $this;
    }

    /**
     * Get cellNumber
     *
     * @return integer
     */
    public function getCellNumber()
    {
        return $this->cell_number;
    }
}

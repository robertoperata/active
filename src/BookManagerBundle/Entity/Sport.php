<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 15/04/16
 * Time: 15.48
 */

namespace BookManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="sport")
 */
class Sport{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $abbreviazione;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceLightsOn;
    

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="sport")
     */
    private $schedules;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="sport")
     */
    private $reservation;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $sportColor;


    public function __construct() {
        $this->schedules = new ArrayCollection();
        $this->reservation = new ArrayCollection();

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Sport
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

   
    /**
     * Add schedule
     *
     * @param \BookManagerBundle\Entity\Schedule $schedule
     *
     * @return Sport
     */
    public function addSchedule(\BookManagerBundle\Entity\Schedule $schedule)
    {
        $this->schedules[] = $schedule;

        return $this;
    }

    /**
     * Remove schedule
     *
     * @param \BookManagerBundle\Entity\Schedule $schedule
     */
    public function removeSchedule(\BookManagerBundle\Entity\Schedule $schedule)
    {
        $this->schedules->removeElement($schedule);
    }

    /**
     * Get schedules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * Set fieldsNumber
     *
     * @param integer $fieldsNumber
     *
     * @return Sport
     */
    public function setFieldsNumber($fieldsNumber)
    {
        $this->fieldsNumber = $fieldsNumber;

        return $this;
    }

    /**
     * Get fieldsNumber
     *
     * @return integer
     */
    public function getFieldsNumber()
    {
        return $this->fieldsNumber;
    }

    /**
     * Set minPlayer
     *
     * @param integer $minPlayer
     *
     * @return Sport
     */
    public function setMinPlayer($minPlayer)
    {
        $this->minPlayer = $minPlayer;

        return $this;
    }

    /**
     * Get minPlayer
     *
     * @return integer
     */
    public function getMinPlayer()
    {
        return $this->minPlayer;
    }

    /**
     * Set maxPlayer
     *
     * @param integer $maxPlayer
     *
     * @return Sport
     */
    public function setMaxPlayer($maxPlayer)
    {
        $this->maxPlayer = $maxPlayer;

        return $this;
    }

    /**
     * Get maxPlayer
     *
     * @return integer
     */
    public function getMaxPlayer()
    {
        return $this->maxPlayer;
    }

    /**
     * Set sportColor
     *
     * @param integer $sportColor
     *
     * @return Sport
     */
    public function setSportColor($sportColor)
    {
        $this->sportColor = $sportColor;

        return $this;
    }

    /**
     * Get sportColor
     *
     * @return integer
     */
    public function getSportColor()
    {
        return $this->sportColor;
    }

    /**
     * Set abbreviazione
     *
     * @param string $abbreviazione
     *
     * @return Sport
     */
    public function setAbbreviazione($abbreviazione)
    {
        $this->abbreviazione = $abbreviazione;

        return $this;
    }

    /**
     * Get abbreviazione
     *
     * @return string
     */
    public function getAbbreviazione()
    {
        return $this->abbreviazione;
    }

    /**
     * Add reservation
     *
     * @param \BookManagerBundle\Entity\Reservation $reservation
     *
     * @return Sport
     */
    public function addReservation(\BookManagerBundle\Entity\Reservation $reservation)
    {
        $this->reservation[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \BookManagerBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\BookManagerBundle\Entity\Reservation $reservation)
    {
        $this->reservation->removeElement($reservation);
    }

    /**
     * Get reservation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Sport
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceLightsOn
     *
     * @param string $priceLightsOn
     *
     * @return Sport
     */
    public function setPriceLightsOn($priceLightsOn)
    {
        $this->priceLightsOn = $priceLightsOn;

        return $this;
    }

    /**
     * Get priceLightsOn
     *
     * @return string
     */
    public function getPriceLightsOn()
    {
        return $this->priceLightsOn;
    }
}

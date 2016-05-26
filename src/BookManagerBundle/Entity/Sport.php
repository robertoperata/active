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
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceResident;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceResidentLightsOn;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceNotResident;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceNotResidentLightsOn;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="sport")
     */
    private $schedules;

    /**
     * @ORM\Column(type="integer")
     */
    private $fieldsNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $minPlayer;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPlayer;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $sportColor;


    public function __construct() {
        $this->schedules = new ArrayCollection();

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
     * Set priceResident
     *
     * @param string $priceResident
     *
     * @return Sport
     */
    public function setPriceResident($priceResident)
    {
        $this->priceResident = $priceResident;

        return $this;
    }

    /**
     * Get priceResident
     *
     * @return string
     */
    public function getPriceResident()
    {
        return $this->priceResident;
    }

    /**
     * Set priceResidentLightsOn
     *
     * @param string $priceResidentLightsOn
     *
     * @return Sport
     */
    public function setPriceResidentLightsOn($priceResidentLightsOn)
    {
        $this->priceResidentLightsOn = $priceResidentLightsOn;

        return $this;
    }

    /**
     * Get priceResidentLightsOn
     *
     * @return string
     */
    public function getPriceResidentLightsOn()
    {
        return $this->priceResidentLightsOn;
    }

    /**
     * Set priceNotResident
     *
     * @param string $priceNotResident
     *
     * @return Sport
     */
    public function setPriceNotResident($priceNotResident)
    {
        $this->priceNotResident = $priceNotResident;

        return $this;
    }

    /**
     * Get priceNotResident
     *
     * @return string
     */
    public function getPriceNotResident()
    {
        return $this->priceNotResident;
    }

    /**
     * Set priceNotResidentLightsOn
     *
     * @param string $priceNotResidentLightsOn
     *
     * @return Sport
     */
    public function setPriceNotResidentLightsOn($priceNotResidentLightsOn)
    {
        $this->priceNotResidentLightsOn = $priceNotResidentLightsOn;

        return $this;
    }

    /**
     * Get priceNotResidentLightsOn
     *
     * @return string
     */
    public function getPriceNotResidentLightsOn()
    {
        return $this->priceNotResidentLightsOn;
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
}

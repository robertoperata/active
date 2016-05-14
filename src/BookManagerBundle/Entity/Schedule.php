<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 27/04/16
 * Time: 21.36
 */

namespace BookManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="schedule")
 */
class Schedule{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="schedules")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    private $sport;

    /**
     *
     * @ORM\Column(type="string", length=3)
     */
    private $days;

    /**
     *  @ORM\Column(type="integer")
     */
    private $days_number;

    /**
     *
     *
     * @ORM\Column(name="valid_from", type="date")
     */
    private $valid_from;


    /**
     * Set days
     *
     * @param string $days
     *
     * @return Schedule
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return string
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set validFrom
     *
     * @param \DateTime $validFrom
     *
     * @return Schedule
     */
    public function setValidFrom($validFrom)
    {
        $this->valid_from = $validFrom;

        return $this;
    }

    /**
     * Get validFrom
     *
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->valid_from;
    }

    /**
     * Set sport
     *
     * @param \BookManagerBundle\Entity\Sport $sport
     *
     * @return Schedule
     */
    public function setSport(\BookManagerBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \BookManagerBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
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
     * Set daysNumber
     *
     * @param integer $daysNumber
     *
     * @return Schedule
     */
    public function setDaysNumber($daysNumber)
    {
        $this->days_number = $daysNumber;

        return $this;
    }

    /**
     * Get daysNumber
     *
     * @return integer
     */
    public function getDaysNumber()
    {
        return $this->days_number;
    }
}

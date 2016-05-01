<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 27/04/16
 * Time: 21.36
 */

namespace BookManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rhumsaa\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="schedule")
 */
class Schedule{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="playing_days")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    private $sport;

    /**
     *
     * @ORM\Column(type="string", length=3, columnDefinition="enum('LUN', 'MAR', 'MER', 'GIO', 'VEN', 'SAB', 'DOM')")
     */
    private $days;

    /**
     *
     *
     * @ORM\Column(name="valid_from", type="date")
     */
    private $valid_from;

    public function __construct()
    {
        if (empty($this->id)) {
            $this->id = Uuid::uuid4();
        }
    }


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
}

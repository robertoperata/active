<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 15/04/16
 * Time: 15.48
 */

namespace BookManagerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rhumsaa\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="sport")
 */
class Sport{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    protected $id;


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
    private $priceRedidentLightsOn;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceNotResident;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $priceNotRedidentLightsOn;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="sport")
     */
    private $schedules;

    public function __construct() {
        $this->schedules = new ArrayCollection();

        if (empty($this->id)) {
            $this->id = Uuid::uuid4();
        }
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
     * Set priceRedidentLightsOn
     *
     * @param string $priceRedidentLightsOn
     *
     * @return Sport
     */
    public function setPriceRedidentLightsOn($priceRedidentLightsOn)
    {
        $this->priceRedidentLightsOn = $priceRedidentLightsOn;

        return $this;
    }

    /**
     * Get priceRedidentLightsOn
     *
     * @return string
     */
    public function getPriceRedidentLightsOn()
    {
        return $this->priceRedidentLightsOn;
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
     * Set priceNotRedidentLightsOn
     *
     * @param string $priceNotRedidentLightsOn
     *
     * @return Sport
     */
    public function setPriceNotRedidentLightsOn($priceNotRedidentLightsOn)
    {
        $this->priceNotRedidentLightsOn = $priceNotRedidentLightsOn;

        return $this;
    }

    /**
     * Get priceNotRedidentLightsOn
     *
     * @return string
     */
    public function getPriceNotRedidentLightsOn()
    {
        return $this->priceNotRedidentLightsOn;
    }

    /**
     * Add playingDay
     *
     * @param \BookManagerBundle\Entity\Schedule $playingDay
     *
     * @return Sport
     */
    public function addPlayingDay(Schedule $playingDay)
    {
        //playing days è una arraycollection, non trattarla come array
        $this->schedules->add($playingDay);

        return $this;
    }

    /**
     * Remove playingDay
     *
     * @param Schedule $playingDay
     */
    public function removePlayingDay(Schedule $playingDay)
    {
        $this->schedules->removeElement($playingDay);
    }

    /**
     * Get playingDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchedules()
    {
        return $this->schedules;
    }
}

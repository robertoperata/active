<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 04/05/16
 * Time: 22.17
 */

namespace BookManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="reservation")
 */
class Reservation{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="reservations")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    private $sport_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     **/
    private $hour;

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="reservations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=13)
     */
    private $cell;

    /**
     * @ORM\Column(type="integer")
     */
    private $residentsNum;
    /**
     * @ORM\Column(type="integer")
     */
    private $notResidentNum;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set hour
     *
     * @param integer $hour
     *
     * @return Reservation
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return integer
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Reservation
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
     * Set cell
     *
     * @param string $cell
     *
     * @return Reservation
     */
    public function setCell($cell)
    {
        $this->cell = $cell;

        return $this;
    }

    /**
     * Get cell
     *
     * @return string
     */
    public function getCell()
    {
        return $this->cell;
    }

    /**
     * Set residentsNum
     *
     * @param integer $residentsNum
     *
     * @return Reservation
     */
    public function setResidentsNum($residentsNum)
    {
        $this->residentsNum = $residentsNum;

        return $this;
    }

    /**
     * Get residentsNum
     *
     * @return integer
     */
    public function getResidentsNum()
    {
        return $this->residentsNum;
    }

    /**
     * Set notResidentNum
     *
     * @param integer $notResidentNum
     *
     * @return Reservation
     */
    public function setNotResidentNum($notResidentNum)
    {
        $this->notResidentNum = $notResidentNum;

        return $this;
    }

    /**
     * Get notResidentNum
     *
     * @return integer
     */
    public function getNotResidentNum()
    {
        return $this->notResidentNum;
    }

    /**
     * Set sportId
     *
     * @param \BookManagerBundle\Entity\Sport $sportId
     *
     * @return Reservation
     */
    public function setSportId(\BookManagerBundle\Entity\Sport $sportId = null)
    {
        $this->sport_id = $sportId;

        return $this;
    }

    /**
     * Get sportId
     *
     * @return \BookManagerBundle\Entity\Sport
     */
    public function getSportId()
    {
        return $this->sport_id;
    }

    /**
     * Set user
     *
     * @param \BookManagerBundle\Entity\User $user
     *
     * @return Reservation
     */
    public function setUser(\BookManagerBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BookManagerBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

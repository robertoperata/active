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
    private $sport;

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
     * @ORM\Column(type="integer")
     */
    private $campo_id;

    /**
     * @ORM\Column(type="string", length=13,  nullable=true)
     */
    private $cell;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="date")
     */
    private $dataPrenotazione;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cancella = false;

    /**
     * @ORM\Column(name="ppTransactionId", type="string",nullable=true)
     */
    private $pptransactionId;

    /**
     * @ORM\Column(name="ppEmail", type="string",nullable=true)
     */
    private $ppEmail;

    /**
     * @ORM\Column(name="ppPayerId", type="string", nullable=true)
     */
    private $ppPayerId;

    /**
     * @ORM\Column(name="totale", type="float", nullable=true)
     */
    private $totale;

    /**
     * @ORM\Column(name="ppTimestamp", type="string", nullable=true)
     */
    private $ppTimestamp;

    /**
     * @ORM\Column(name="ipClinet", type="string")
     */
    private $ip;

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
     * Set sportId
     *
     * @param \BookManagerBundle\Entity\Sport $sportId
     *
     * @return Reservation
     */
    public function setSport(\BookManagerBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sportId
     *
     * @return \BookManagerBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
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

    /**
     * Set dataPrenotazione
     *
     * @param \DateTime $dataPrenotazione
     *
     * @return Reservation
     */
    public function setDataPrenotazione($dataPrenotazione)
    {
        $this->dataPrenotazione = $dataPrenotazione;

        return $this;
    }

    /**
     * Get dataPrenotazione
     *
     * @return \DateTime
     */
    public function getDataPrenotazione()
    {
        return $this->dataPrenotazione;
    }



    /**
     * Set campoId
     *
     * @param integer $campoId
     *
     * @return Reservation
     */
    public function setCampoId($campoId)
    {
        $this->campo_id = $campoId;

        return $this;
    }

    /**
     * Get campoId
     *
     * @return integer
     */
    public function getCampoId()
    {
        return $this->campo_id;
    }

    /**
     * Set player
     *
     * @param integer $player
     *
     * @return Reservation
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return integer
     */
    public function getPlayer()
    {
        return $this->player;
    }


    /**
     * Set players
     *
     * @param integer $players
     *
     * @return Reservation
     */
    public function setPlayers($players)
    {
        $this->players = $players;

        return $this;
    }

    /**
     * Get players
     *
     * @return integer
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Reservation
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set cancella
     *
     * @param  $cancella
     *
     * @return Reservation
     */
    public function setCancella($cancella)
    {
        $this->cancella = $cancella;

        return $this;
    }

    /**
     * Get cancella
     *
     * @return boolean
     */
    public function getCancella()
    {
        return $this->cancella;
    }

    /**
     * Set pptransactionId
     *
     * @param string $pptransactionId
     *
     * @return Reservation
     */
    public function setPptransactionId($pptransactionId)
    {
        $this->pptransactionId = $pptransactionId;

        return $this;
    }

    /**
     * Get pptransactionId
     *
     * @return string
     */
    public function getPptransactionId()
    {
        return $this->pptransactionId;
    }

    /**
     * Set ppEmail
     *
     * @param string $ppEmail
     *
     * @return Reservation
     */
    public function setPpEmail($ppEmail)
    {
        $this->ppEmail = $ppEmail;

        return $this;
    }

    /**
     * Get ppEmail
     *
     * @return string
     */
    public function getPpEmail()
    {
        return $this->ppEmail;
    }

    /**
     * Set ppPayerId
     *
     * @param string $ppPayerId
     *
     * @return Reservation
     */
    public function setPpPayerId($ppPayerId)
    {
        $this->ppPayerId = $ppPayerId;

        return $this;
    }

    /**
     * Get ppPayerId
     *
     * @return string
     */
    public function getPpPayerId()
    {
        return $this->ppPayerId;
    }

    /**
     * Set totale
     *
     * @param float $totale
     *
     * @return Reservation
     */
    public function setTotale($totale)
    {
        $this->totale = $totale;

        return $this;
    }

    /**
     * Get totale
     *
     * @return float
     */
    public function getTotale()
    {
        return $this->totale;
    }

    /**
     * Set ppTimestamp
     *
     * @param string $ppTimestamp
     *
     * @return Reservation
     */
    public function setPpTimestamp($ppTimestamp)
    {
        $this->ppTimestamp = $ppTimestamp;

        return $this;
    }

    /**
     * Get ppTimestamp
     *
     * @return string
     */
    public function getPpTimestamp()
    {
        return $this->ppTimestamp;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Reservation
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}

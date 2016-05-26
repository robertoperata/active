<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 11/05/16
 * Time: 20.23
 */

namespace BookManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="preferenze")
 */
class OrariPreferenze{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $apertura;

    /**
     * @ORM\Column(type="integer")
     */
    private $chiusura;

    /**
     * @ORM\Column(type="integer")
     */
    private $notturno;


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
     * @return OrariPreferenze
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
     * Set aperutra
     *
     * @param integer $aperutra
     *
     * @return OrariPreferenze
     */
    public function setAperutra($aperutra)
    {
        $this->aperutra = $aperutra;

        return $this;
    }

    /**
     * Get aperutra
     *
     * @return integer
     */
    public function getAperutra()
    {
        return $this->aperutra;
    }

    /**
     * Set chiusura
     *
     * @param integer $chiusura
     *
     * @return OrariPreferenze
     */
    public function setChiusura($chiusura)
    {
        $this->chiusura = $chiusura;

        return $this;
    }

    /**
     * Get chiusura
     *
     * @return integer
     */
    public function getChiusura()
    {
        return $this->chiusura;
    }

    /**
     * Set notturno
     *
     * @param integer $notturno
     *
     * @return OrariPreferenze
     */
    public function setNotturno($notturno)
    {
        $this->notturno = $notturno;

        return $this;
    }

    /**
     * Get notturno
     *
     * @return integer
     */
    public function getNotturno()
    {
        return $this->notturno;
    }

    /**
     * Set apertura
     *
     * @param integer $apertura
     *
     * @return OrariPreferenze
     */
    public function setApertura($apertura)
    {
        $this->apertura = $apertura;

        return $this;
    }

    /**
     * Get apertura
     *
     * @return integer
     */
    public function getApertura()
    {
        return $this->apertura;
    }
}

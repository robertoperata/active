<?php


namespace BookManagerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rhumsaa\Uuid\Uuid;

/**
 * Class Order
 * @package BookManagerBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="schedule_order")
 */
class Order
{

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @var Uuid
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BookManagerBundle\Entity\User",inversedBy="orders")
     * @var User $user
     */
    protected $customer;


    /**
     * @ORM\OneToMany(targetEntity="BookManagerBundle\Entity\Schedule",mappedBy="orders")
     * @var ArrayCollection
     */
    protected $schedules;

    /**
     * @var string
     */
    protected $paypalTransactionId;

    /**
     * Order constructor.
     * @param User            $user
     * @param ArrayCollection $schedules
     */
    public function __construct(User $user, ArrayCollection $schedules)
    {
        if (empty($this->id)) {
            $this->id = Uuid::uuid4();
        }
        $this->customer = $user;
        $this->schedules = $schedules;
    }

    /**
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return User
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param User $customer
     * @return $this
     */
    public function setCustomer(User $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * @param Schedule $schedules
     * @return $this
     */
    public function setSchedules(Schedule $schedules)
    {
        $this->schedules = $schedules;

        return $this;
    }

    /**
     * @param string $paypalTransactionId
     * @return Order
     */
    public function setPayPalTransactionId($paypalTransactionId)
    {
        $this->paypalTransactionId = $paypalTransactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayPalTransactionId()
    {
        return $this->paypalTransactionId;
    }


}

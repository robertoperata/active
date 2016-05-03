<?php
namespace Tests\AppBundle\Controller;

use BookManagerBundle\Entity\Order;
use BookManagerBundle\Entity\Schedule;
use BookManagerBundle\Entity\Sport;
use BookManagerBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PaymentControllerTest extends WebTestCase
{
    const SPORT_NAME = 'sport name';
    const PRICE_NOT_RESIDENT = 10.2;
    const PRICENOTRESIDENTLIGHTSON = 11.1;
    const PRICERESIDENT = 9.5;
    const PRICERESIDENTLIGHTSON = 9.9;
    /**
     * @var UserManagerInterface
     */
    protected $usermanager;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    private $order;

    const USER_PLAIN_PASSWORD = self::USERNAME;

    const USERNAME = 'pippo';

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->container = static::$kernel->getContainer();
        $this->usermanager = $this->container->get('fos_user.user_manager');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->order = $this->getOrderFixture();

        //we created an order
    }

    public function testPaymentStart()
    {
        //we did login (also created the user if needed)

        //we got to `payment_start` route with the id of the order
        //the order entity gets updated with data coming from paypal
        //we get a 302 to paypal
    }

    private function getOrderFixture()
    {
        $schedule = $this->getScheduleFixture();
        $user = $this->getUserFixture();
        $order = new Order($user, new ArrayCollection([$schedule]));
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }

    private function getUserFixture()
    {
        $user = new User();
        $user->setPlainPassword(self::USER_PLAIN_PASSWORD)
            ->setUsername(self::USERNAME.md5(time()))
            ->setEmail(md5(time()).'@somewhere.over.the.rainbow')
            ->setEnabled(true);

        $this->usermanager->updateUser($user);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    private function getScheduleFixture()
    {
        $sport = $this->getSportFixture();
        $schedule = new Schedule();
        $schedule->setDay(Schedule::SCHEDULE_DAY_MON)
            ->setSport($sport)
            ->setValidFrom(new \DateTime('now'));
        $this->em->persist($schedule);
        $this->em->flush();
        return $schedule;
    }

    private function getSportFixture()
    {
        $sport = new Sport();
        $sport->setName(self::SPORT_NAME)
            ->setPriceNotResident(self::PRICE_NOT_RESIDENT)
            ->setPriceNotResidentLightsOn(self::PRICENOTRESIDENTLIGHTSON)
            ->setPriceResident(self::PRICERESIDENT)
            ->setPriceResidentLightsOn(self::PRICERESIDENTLIGHTSON);
        $this->em->persist($sport);
        $this->em->flush();
        return $sport;
    }
}

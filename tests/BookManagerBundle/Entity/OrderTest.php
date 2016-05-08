<?php
namespace BookManagerBundle\Tests\Entity;

use BookManagerBundle\Entity\Order;
use BookManagerBundle\Entity\Schedule;
use BookManagerBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class OrderTest
 * @package BookManagerBundle\Tests\Entity
 */
class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testOrderClassExists()
    {
        $user = new User();
        $schedule = new Schedule();
        $schedules = new ArrayCollection([ $schedule ]);
        $order = new Order($user, $schedules);
        $this->assertNotNull($order);
    }
}

<?php


use BookManagerBundle\Entity\Schedule;
use BookManagerBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function testOrderClassExists()
    {
        $user = new User();
        $schedule = new Schedule();
        $schedules = new ArrayCollection([ $schedule ]);
        $order = new Order($user, $schedules);
        $this->assertNotNull($order);
    }
}

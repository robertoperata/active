<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    public function testPaymentStart()
    {
        //we did login (also created the user if needed)
        //we created an order
        //we got to `payment_start` route with the id of the order
        //the order entity gets updated with data coming from paypal
        //we get a 302 to paypal
    }
}

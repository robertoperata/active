<?php

namespace BookManagerBundle\Controller;

use BookManagerBundle\Entity\Order;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Payum\Core\Model\CreditCard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaymentController
 * @package BookManagerBundle\Controller
 * @Route("/payment")
 */
class PaymentController extends Controller
{

    /**
     * @Route("/{id}/start",name="active_payment_start")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Order $order
     * @param Request $request
     * @return null
     */
    public function startPaymentAction(Order $order, Request $request)
    {
        //actually connect to paypal and retrieve order data;

        //FIXME: TESTME!!!! <= Roberto this is your homework ;)

        //Here's the aactual Paypal doc -> https://developer.paypal.com/webapps/developer/docs/api/
        //php vendor/paypal/rest-api-sdk-php/sample/payments/CreatePaymentUsingPayPal.php

        $apiContext = new ApiContext(new OAuthTokenCredential(
            "<CLIENT_ID>", "<CLIENT_SECRET>"));

        $addr = new Address();
        $addr->setLine1('52 N Main ST');
        $addr->setCity('Johnstown');
        $addr->setCountry_code('US');
        $addr->setPostal_code('43210');
        $addr->setState('OH');
//
//        $card = new CreditCard();
//        $card->setNumber('4417119669820331');
//        $card->setType('visa');
//        $card->setExpire_month('11');
//        $card->setExpire_year('2018');
//        $card->setCvv2('874');
//        $card->setFirst_name('Joe');
//        $card->setLast_name('Shopper');
//        $card->setBilling_address($addr);

//        $fi = new FundingInstrument();
//        $fi->setCredit_card($card);

        $payer = new Payer();
        $payer->setPayment_method('credit_card');
//        $payer->setFunding_instruments(array($fi)); $this is optional and since we don't want to store CC info it will be blank

        $amountDetails = new Details();
        $amountDetails->setSubtotal('7.41');
        $amountDetails->setTax('0.03');
        $amountDetails->setShipping('0.03');

        $amount = new Amount();
        $amount->setCurrency('USD');
        $amount->setTotal('7.47');
        $amount->setDetails($amountDetails);

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('This is the payment transaction description.');

        $payment = new Payment();
        $payment->setIntent('sale');
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));
        $payment->setRedirectUrls(/* Our URLs (return url and cancel url */);

        $payment->create($apiContext);

        //At this point we have the info about the payment (with the redirect url)
        // before sending the user to paypal that store the payment details in the order entity
        // The user authorizes the payment, gets back to return url
        //At this point we get the payment details, and tell paypal to actually perform the payment
        //Then we get the info about the payment being done

        return new RedirectResponse('https://paypal.com');
    }
}

<?php

namespace BookManagerBundle\Controller;


use BookManagerBundle\Entity\Payment;
use BookManagerBundle\Entity\PaymentDetails;
use Payum\Core\Request\GetHumanStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route("/pagamenti")
*/
class PaymentController extends Controller
{

    /**
     *
     * @Route("/prepare", name="pagamento_prepare")
     * @Method("GET")
     */
    public function prepareAction()
    {
        $gatewayName = 'paypal_express_checkout';

//        $storage = $this->get('payum')->getStorage(PaymentDetails::class);
        $storage = $this->get('payum')->getStorage(Payment::class);


        $payment = new Payment();

        $router = $this->get('router');
        $return = $router->generate('pagamento_done');


        /** @var \BookManagerBundle\Entity\PaymentDetails $details */
        $details = [
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'ZMB',
//            'RETURNURL' => $return,
//            'CANCELURL' => 'http://cancel.url',
            'PAYMENTREQUEST_0_AMT' => 1.23
        ];


        $payment->setCurrencyCode('USD');
        $payment->setTotalAmount(1987);
        $payment->setDetails($details);

        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'pagamento_done' // the route to redirect after capture;
        );
        $capture = $captureToken->getTargetUrl();

        return $this->redirect($capture);
    }

    /**
     *
     * @Route("/done", name="pagamento_done")
     * @Method("GET")
     */
    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum')->getHttpRequestVerifier()->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$payment = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
            ),
        ));
    }
}
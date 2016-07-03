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
use Symfony\Component\HttpFoundation\Response;

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
       // $request = Request::createFromGlobals();
        $gatewayName = 'paypal_express_checkout';
        $session = $this->container->get('session');

        $reservation = $session->get('reservation');

//        $storage = $this->get('payum')->getStorage(PaymentDetails::class);
        $storage = $this->get('payum')->getStorage(Payment::class);


        $payment = new Payment();

        $router = $this->get('router');
        $return = $router->generate('pagamento_done');

        $totale = $session->get('prezzo')*100;
        /** @var \BookManagerBundle\Entity\PaymentDetails $details */
        $details = [
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR',
//            'RETURNURL' => $return,
//            'CANCELURL' => 'http://cancel.url',
            'PAYMENTREQUEST_0_AMT' => $totale,
            'NOSHIPPING' => 1
        ];
        //$paymentDetails->setNoshipping(Api::NOSHIPPING_NOT_DISPLAY_ADDRESS);

        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($totale);
        $payment->setDescription("Prenotazione ".$reservation->getSport()->getName().": ".$reservation->getDataPrenotazione()->format('d-m'). " alle ore " . $reservation->getHour().":00");
      //  $payment->setDescription("Prenotazione " . $reservation->getSport()->getName());
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
        /*
        $response = new Response(json_encode(array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
            ),)));

        return $this->redirectToRoute('checkout', array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
            ),));
        */
       $pagamento = array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
            ),
        );

        $session = $this->container->get('session');

        $reservation = $session->get('reservation');
        $sportid =  $session->get('sportid');

        $dbManager =    $this->get('app.dbmanager');

        $sport = $dbManager->getSport($sportid);
        $reservation->setSport($sport);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reservation->setUser($user);
        $reservation->setTotale($pagamento['payment']['details']['AMT']);
        $reservation->setPpEmail($pagamento['payment']['details']['EMAIL']);
        $reservation->setPptransactionId($pagamento['payment']['details']['TRANSACTIONID']);
        $reservation->setPpPayerId($pagamento['payment']['details']['PAYERID']);
        $reservation->setIp($request->getClientIp());
        $reservation->setPpTimestamp($pagamento['payment']['details']['TIMESTAMP']);
        $campo_id = $dbManager->getReservationPerSportAndDay($reservation->getSport(), $reservation->getDataPrenotazione(), $reservation->getHour());

        $numeroCampo = 1;
        if(sizeof($campo_id) > 0){
            $numeroCampo = sizeof($campo_id) + 1;
        }
        $reservation->setCampoId($numeroCampo);


        $response = new Response();
        $response->setStatusCode('200');
        try{
            $prenotazione = $dbManager->saveReservation($reservation);
            $response->setContent(json_encode($prenotazione));
        }catch (Exception $e){
            $response->setStatusCode('400');
        }

        //invio messaggio Email
        $email = $user->getEmail();
        $message = \Swift_Message::newInstance()
            ->setSubject('Prenotazione')
            ->setFrom('info@letiziasportrelax.it')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'email/conferma.html.twig',
                    array('reservation' => $reservation)
                ),
                'text/plain'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $this->get('mailer')->send($message);
        return $this->render('cli/checkout.html.twig', array(
            'sport'=> $sport->getName(),
            'giorno'=> $reservation->getDataPrenotazione()->format('d-m'),
            'ora' => $reservation->getHour()
        ));

    }





}
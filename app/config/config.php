<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 28/06/16
 * Time: 22.12
 */

use Payum\Core\PayumBuilder;
use Payum\Core\Payum;
use Payum\Core\Model\Payment;

$paymentClass = Payment::class;

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()
    ->addGateway('aGateway', [
        'factory' => 'offline',
    ])

    ->getPayum()
;
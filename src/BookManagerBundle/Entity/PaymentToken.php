<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 29/06/16
 * Time: 18.54
 */

namespace BookManagerBundle\Entity;

use Payum\Core\Model\Token;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class PaymentToken extends Token
{


}
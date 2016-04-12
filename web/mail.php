<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 12/04/16
 * Time: 19.21
 */
$message = "Line 1\r\nLine 2\r\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Send
$bool = mail('robypriz@gmail.com', 'My Subject', $message);
var_dump($bool);
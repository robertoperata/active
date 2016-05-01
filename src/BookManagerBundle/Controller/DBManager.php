<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 01/05/16
 * Time: 15.59
 */

namespace BookManagerBundle\Controller;


use BookManagerBundle\Entity\Schedule;

class DBManager{

    private $repository;

    public function __constructor(){
        $repository = $this->getDoctrine();


    }



    public static function insertSchedule($sport, $date){
        $repository->getRepository('BookManagerBundle:Schedule');
        $schedule = new Schedule();
        $schedule->setSport($sport);
        $schedule->setValidFrom($date);
        $schedule->setDays(day);
        $em = $this->getDoctrine()->getManager();
        $em->persist($schedule);
        $em->flush();
    }

}
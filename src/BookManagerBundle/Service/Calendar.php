<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 05/05/16
 * Time: 20.25
 */

namespace BookManagerBundle\Service;


class Calendar{

    public function createDefaultHolidayCalendar(){

        $currentYear = date("Y");
        $nextYear = $currentYear + 1;
        $currentMounth = date("m");

        $ottoDicembre = new \DateTime($currentYear."-12-08", new \DateTimeZone('Europe/Rome'));

        $venticinqueDicembre = new \DateTime($currentYear."-12-25", new \DateTimeZone('Europe/Rome'));
        $ventiseiDicembre = new \DateTime($currentYear."-12-26", new \DateTimeZone('Europe/Rome'));
        $capodanno = new \DateTime($nextYear."-01-01", new \DateTimeZone('Europe/Rome'));
        if($currentMounth > 4){
            $venticiqueAprile = new \DateTime($nextYear."-04-25", new \DateTimeZone('Europe/Rome'));
        }else{
            $venticiqueAprile = new \DateTime($currentYear."-04-25", new \DateTimeZone('Europe/Rome'));
        }
        if($currentMounth > 5){
            $primoMaggio = new \DateTime($nextYear."-04-25", new \DateTimeZone('Europe/Rome'));
        }else{
            $primoMaggio = new \DateTime($currentYear."-04-25", new \DateTimeZone('Europe/Rome'));
        }
        if($currentMounth > 6){
            $duegiugno = new \DateTime($nextYear."-06-02", new \DateTimeZone('Europe/Rome'));
        }else{
            $duegiugno = new \DateTime($currentYear."-06-02", new \DateTimeZone('Europe/Rome'));
        }
        if($currentMounth > 8){
            $quindiciAgosto = new \DateTime($nextYear."-08-15", new \DateTimeZone('Europe/Rome'));
        }else{
            $quindiciAgosto = new \DateTime($currentYear."-08-15", new \DateTimeZone('Europe/Rome'));
        }
/*
        $pasqua = easter_date($currentYear);
        $pasquetta = date($pasqua)+1;
        $now = new \DateTime();
        if($pasqua < $now){
            $pasqua = easter_date($nextYear);
            $pasquetta = date($pasqua)+1;
        }
*/
        $closedDays = array();
        $closedDays[$capodanno->getTimestamp()]		= false;
 //       $closedDays[$pasqua->getTimestamp()]		= false;
 //       $closedDays[$pasquetta]		= false;
        $closedDays[$venticiqueAprile->getTimestamp()]=false;
        $closedDays[$primoMaggio->getTimestamp()]	= false;
        $closedDays[$duegiugno->getTimestamp()]		= false;
        $closedDays[$quindiciAgosto->getTimestamp()]= false;
        $closedDays[$ottoDicembre->getTimestamp()]	= false;
        $closedDays[$venticinqueDicembre->getTimestamp()]=false;
        $closedDays[$ventiseiDicembre->getTimestamp()]=false;

        ksort($closedDays);
        return $closedDays;
    }

    public function createClosingCalendar($defaultHolidays, $savedSchedule){
        $a = array_fill_keys($savedSchedule, true);
        $closingDays = array_merge($a, $defaultHolidays);
        return $closingDays;
    }

}
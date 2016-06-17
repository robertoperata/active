<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 02/05/16
 * Time: 20.23
 */

namespace BookManagerBundle\Service;


use BookManagerBundle\Entity\ClosingDays;
use BookManagerBundle\Entity\Reservation;
use BookManagerBundle\Entity\Schedule;
use BookManagerBundle\Entity\OrariPreferenze;
use BookManagerBundle\Entity\Sport;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

class DBManager
{
    private $em;

    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }

    public function getAllSchedules(){
        return  $this->em->getRepository('BookManagerBundle:Schedule')->findAll();
    }

    public function getAllSports(){
        return $this->em->getRepository('BookManagerBundle:Sport')->findAll();
    }

    public function getSportScheduledForDay( $sport, $day){

        $qb = $this->em->createQueryBuilder();

        $result =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->andWhere('s.days = ?2')
            ->setParameter(1,$sport)
            ->setParameter(2,$day)
            ->setMaxResults(1)
            ->getQuery()->execute();

        return $result;
    }


    public function getAllSportsForDay(\DateTime $day){
        $day_number = $day->format('w');
        $day->format('Y-m-d');
        $valid_from = $this->getDataTabelloneInCorso($day, $day_number);

        $qb = $this->em->createQueryBuilder();
      //  $giornoSettimana = date('w', $day->getTimestamp());
        //$day->format('Y-m-d');

        $qb ->select('a', 'u')
            ->from('BookManagerBundle:Schedule', 'a')
            ->leftJoin('a.sport', 'u')
            ->where('a.days_number = ?1')
            ->andWhere('a.valid_from <= ?2')
            ->setParameter(1,$day_number)
            ->setParameter(2,$valid_from);

        return $qb->getQuery()->getResult();
    }


    public function saveSport(Sport $sport){
        $this->em->persist($sport);
        $this->em->flush();
        return $sport->getId();
    }



    public function getDaysPerSport($sport){

        $qb = $this->em->createQueryBuilder();

        $result =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->setParameter(1,$sport)
            ->getQuery()->execute();

         return $result;
    }

    public function getSport($sport){
        return $this->em->getRepository('BookManagerBundle:Sport')->find($sport);
    }


    public function saveSchedule(Schedule $schedule){
        $this->em->persist($schedule);
        $this->em->flush();
        return $schedule->getId();
    }

    public function deleteSchedule($id){
        $schedule = $this->em->getRepository('BookManagerBundle:Schedule')->find($id);
        $this->em->remove($schedule);
        $this->em->flush();
    }

        /*
    public function saveSchedule($sport, $day){
        $elencoGiogni = [1=>"LUN", 2=>"MAR", 3=>"MER", 4=>"GIO", 5=>"VEN", 6=>"SAB", 0=>"DOM"];

        $sport = $this->getSport($sport);

        $date = new \DateTime();
        $date->format('Y-m-d');

        $day_number = array_search($day, $elencoGiogni);
        $schedule = new Schedule();
        $schedule->setSport($sport);
        $schedule->setValidFrom($date);
        $schedule->setDays($day);
        $schedule->setDaysNumber($day_number);
        $this->em->persist($schedule);
        $this->em->flush();
    }

    public function deleteSchedule($schedule){
        $this->em->createQuery(
            'DELETE BookManagerBundle:Schedule s
               WHERE s.id = :scheduledId')
            ->setParameter("scheduledId", $schedule[0]->getId())->execute();
    }
        */


    /**
     * Calendario
     */

    public function addClosingDay($date){
        $closingDate = new ClosingDays();

        $closingDate->setDate($date);

        $this->em->persist($closingDate);
        $this->em->flush();
    }



    public function  deleteClosedDay($date){

        $closingDay = $this->em->getRepository('BookManagerBundle:ClosingDays')->findBy(
            array('date' => $date),
            array('date' => 'ASC'),
            1
        );

        $this->em->remove($closingDay[0]);
        $this->em->flush();


    }

    public function getClosingDays($today){
        $fields = array('c.date');
        $qb = $this->em->createQueryBuilder();

        $result =$qb->select($fields)
            ->from('BookManagerBundle:ClosingDays', 'c')
            ->where('c.date > ?1')
            ->setParameter(1,$today)
            ->getQuery()->execute();

        return $result;
    }

    public function getTimePreferencies($today){

        $qb = $this->em->createQueryBuilder();
        $timePreferenciesBefore = $qb->select('o')
            ->from('BookManagerBundle:OrariPreferenze', 'o')
            ->where('o.date <= ?1')
            ->setMaxResults(1)
            ->orderBy('o.date', 'DESC')
            ->setParameter(1, $today)
            ->getQuery()->execute();
        $timePreferenciesAfter = $qb->select('o')
            ->from('BookManagerBundle:OrariPreferenze', 'p')
            ->where('p.date > ?1')
            ->setParameter(1, $today)
            ->getQuery()->execute();

        return array_merge($timePreferenciesBefore ,$timePreferenciesAfter) ;
    }

    public function deletePreference($id){
         $this->em->createQuery(
            'DELETE BookManagerBundle:OrariPreferenze s
           WHERE s.id = :preferenceId')
            ->setParameter("preferenceId", $id)->execute();
    }

    public function savePreferenza(OrariPreferenze $preferenza){
        $this->em->persist($preferenza);
        $this->em->flush();
    }

    public function getPrenotazioniPerSport(Sport $sport){

        $result =  $this->em->createQuery(
            'SELECT s FROM BookManagerBundle:Reservation s
           WHERE s.sport = :sportID AND s.date >= CURRENT_DATE()')
            ->setParameter("sportID", $sport->getId())->execute();

        return $result;
    }

    private function getDataTabelloneInCorso(\DateTime $day, $day_number){
        $qb = $this->em->createQueryBuilder();

        $preferenzeAttuali = $qb->select('o')
            ->from('BookManagerBundle:Schedule', 'o')
            ->where('o.valid_from <= ?1')
            ->andWhere('o.days_number = ?2')
            ->orderBy('o.valid_from', 'DESC')
            ->setMaxResults(1)
            ->setParameter(1, $day)
            ->setParameter(2, $day_number)
            ->getQuery()->execute();

        if(sizeof($preferenzeAttuali) > 0){
            $valid_from = $preferenzeAttuali[0]->getValidFrom();
        }else{
            $valid_from = $day;
        }
        return $valid_from;
    }


    public function getPrenotazioniTabellone($day_number){
        $today = new \DateTime();
        $today->format('Y-m-d');
        $valid_from = $this->getDataTabelloneInCorso($today, $day_number);

        $qb2 = $this->em->createQueryBuilder();
        $tabellonePreferenze = $qb2->select('p')
            ->from('BookManagerBundle:Schedule', 'p')
            ->where('p.valid_from >= ?1')
            ->andWhere('p.days_number = ?2')
            ->orderBy('p.valid_from', 'ASC')
            ->setParameter(1, $valid_from)
            ->setParameter(2, $day_number)
            ->getQuery()->execute();

        return $tabellonePreferenze ;
    }

    public function getReservationPerSportAndDay(Sport $sport, \DateTime $dateTime, $ora){
        $qb = $this->em->createQueryBuilder();

       // $dateTime = $dateTime->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione = ?1')
            ->andWhere('r.sport = ?2')
            ->andWhere('r.hour = ?3')
            ->setParameter(1,$dateTime)
            ->setParameter(2, $sport->getId())
            ->setParameter(3, $ora)
            ->getQuery()->execute();
        return $result;
    }

    public function getPrenotazioniPerDay(\DateTime $dateTime){


        $qb = $this->em->createQueryBuilder();

        $dateTime->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione = ?1')
            ->setParameter(1,$dateTime)
            ->getQuery()->execute();
        return $result;

    }

    public function getPrenotazioniFromToday(){
        $qb = $this->em->createQueryBuilder();

        $today = new \DateTime();
        $today->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.date > ?1')
            ->setParameter(1,$today)
            ->getQuery()->execute();
        return $result;
    }



    public function saveReservation(Reservation $reservation){
        $this->em->merge($reservation);
        $this->em->flush();
    }




    //TODO: metodo pessimo che fa 2 query da migliorare
    public function getOrariApertura(){
        $qb = $this->em->createQueryBuilder();
        $today = new \DateTime();
        $today->format('Y-m-d');
        $result = null;
        $subset =$qb->select('r')
            ->from('BookManagerBundle:OrariPreferenze', 'r')
            ->where('r.date <= ?1')
            ->orderBy('r.date', 'DESC')
            ->setMaxResults(1)
            ->setParameter(1,$today)
            ->getQuery()->execute();
        if(sizeof($subset) > 0){
            $data = $subset[0];
            $result =$qb->select('r')
                ->from('BookManagerBundle:OrariPreferenze', 'p')
                ->where('p.date >= ?1')
                ->setParameter(1,$data->getDate())
                ->getQuery()->execute();
        }
        return $result;
    }

    public function getOrariAperturaPerGriono(\DateTime $date){
        $qb = $this->em->createQueryBuilder();
        $result  = $qb->select('r')
            ->from('BookManagerBundle:OrariPreferenze', 'r')
            ->where('r.date <= ?1')
            ->orderBy('r.date', 'DESC')
            ->setMaxResults(1)
            ->setParameter(1, $date)
            ->getQuery()->execute();
        return $result;
    }


}
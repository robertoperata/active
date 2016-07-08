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
use BookManagerBundle\Service\User;

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

    public function getSportScheduledForDay(Sport $sport,\DateTime $day){

        $qb = $this->em->createQueryBuilder();

        $result =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->andWhere('s.valid_from <= ?2')
            ->andWhere('s.days_number = ?3')
            ->setParameter(1,$sport)
            ->setParameter(2,$day->format('Y-m-d'))
            ->setParameter(3,$day->format('w'))
            ->orderBy('s.valid_from', 'DESC')
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
            ->andWhere('a.valid_from = ?2')
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



    public function deleteReservation(Reservation $reservation){
        $this->em->remove($reservation);
        $this->em->flush();
    }

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
    public function checkAvailableDay(\DateTime $giornoPrenotazoine){
        $qb = $this->em->createQueryBuilder();

        $result =$qb->select('c')
            ->from('BookManagerBundle:ClosingDays', 'c')
            ->where('c.date = ?1')
            ->setParameter(1,$giornoPrenotazoine->getTimestamp())
            ->getQuery()->execute();
        return $result;
    }


    public function getClosingDays(\DateTime $today){
        $fields = array('c.date');

        $qb = $this->em->createQueryBuilder();

        $result =$qb->select($fields)
            ->from('BookManagerBundle:ClosingDays', 'c')
            ->where('c.date > ?1')
            ->setParameter(1,$today->getTimestamp())
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
        $qb2 = $this->em->createQueryBuilder();
        $timePreferenciesAfter = $qb2->select('p')
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
        return $preferenza->getId();
    }

    public function getPrenotazioniPerSport(Sport $sport){

        $result =  $this->em->createQuery(
            'SELECT s FROM BookManagerBundle:Reservation s
           WHERE s.sport = :sportID AND s.date >= CURRENT_DATE() AND s.cancella = 0')
            ->setParameter("sportID", $sport->getId())->execute();

        return $result;
    }


    public function getDataValiditaPerGiorno(\DateTime $day, $daynumber){
        $day->format('Y-m-d');
        $qb = $this->em->createQueryBuilder();
        $result =$qb->select('r')
            ->from('BookManagerBundle:Schedule', 'r')
            ->where('r.valid_from <= ?1')
            ->andWhere('r.days_number = ?2')
            ->orderBy('r.valid_from', 'DESC')
            ->setParameter(1,$day)
            ->setParameter(2, $daynumber)
            ->setMaxResults(1)
            ->getQuery()->execute();


        if(sizeof($result) > 0){
            $valid_from = $result[0]->getValidFrom();
        }
        return $valid_from;

    }

    public function getSportFromSchedule(\DateTime $valid_from,  $day_num){
        $qb = $this->em->createQueryBuilder();
        $result =$qb->select('r')
            ->from('BookManagerBundle:Schedule', 'r')
            ->where('r.valid_from >= ?1')
            ->andWhere('r.days_number = ?2')
            ->orderBy('r.valid_from', 'ASC')
            ->setParameter(1,$valid_from)
            ->setParameter(2, $day_num)
            ->getQuery()->execute();
        return $result;
    }

    public  function getUserReservations($user_id){
            $qb = $this->em->createQueryBuilder();
            $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.user >= ?1')
            ->andWhere('r.dataPrenotazione >= CURRENT_DATE()' )
            ->andWhere('r.cancella = 0')
            ->orderBy('r.dataPrenotazione', 'ASC')
            ->setParameter(1,$user_id)
            ->getQuery()->execute();
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
            ->andWhere('r.cancella = 0')
            ->setParameter(1,$dateTime)
            ->setParameter(2, $sport->getId())
            ->setParameter(3, $ora)
            ->getQuery()->execute();
        return $result;
    }

    public function getCampiDisponibiliPerSportEGiorno(Sport $sport, \DateTime $dateTime){
        $qb = $this->em->createQueryBuilder();
/*
        $sql = 'SELECT *, count(hour) FROM activedb.reservation where data_prenotazione = :data_prenotazione and sport_id = :sportID group by hour';
        $result =  $this->em->createQuery($sql )->setParameter("data_prenotazione", $dateTime->format('Y-m-d'))->setParameter("sportID", $sport->getId())->execute();
*/

        $result = $qb->select('COUNT(r.hour) as num, r.hour')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione = ?1')
            ->andWhere('r.sport = ?2')
            ->groupBy('r.hour')
            ->setParameter(1,$dateTime)
            ->setParameter(2, $sport->getId())
            ->getQuery()->execute();
        return $result;


    }

    public function getPrenotazioniPerDay(\DateTime $dateTime){


        $qb = $this->em->createQueryBuilder();

        $giornoPrenotazione = $dateTime->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione = ?1')
            ->andWhere('r.cancella = 0')
            ->setParameter(1,$giornoPrenotazione)
            ->getQuery()->execute();
        return $result;

    }


    public function getPrenotazioniPerDayEHour(\DateTime $dateTime, $hour){


        $qb = $this->em->createQueryBuilder();

        $giornoPrenotazione = $dateTime->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione = ?1')
            ->andWhere('r.hour = ?2')
            ->andWhere('r.cancella = 0')
            ->setParameter(1,$giornoPrenotazione)
            ->setParameter(2,$hour)
            ->getQuery()->execute();
        return $result;
    }

    public function getPrenotazioniProssimi5Giorni(\DateTime $dateTime){
        $qb = $this->em->createQueryBuilder();

        $giornoPrenotazione = $dateTime->format('Y-m-d');
        $finoData = $dateTime->add(new \DateInterval('P5D'));
        $finoData->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione >= ?1')
            ->andWhere('r.dataPrenotazione < ?2')
            ->andWhere('r.cancella = 0')
            ->orderBy('r.dataPrenotazione', 'ASC')
           ->addOrderBy('r.hour', 'ASC')
            ->setParameter(1,$giornoPrenotazione)
            ->setParameter(2,$finoData)
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
            ->andWhere('r.cancella = 0')
            ->setParameter(1,$today)
            ->getQuery()->execute();
        return $result;
    }


    public function getReservationById($idPrenotazione){
        return $this->em->getRepository('BookManagerBundle:Reservation')->find($idPrenotazione);
    }

    public function saveReservation(Reservation $reservation){
        $this->em->persist($reservation);
        $this->em->flush();
        return $reservation->getId();
    }


    public function cancellaPrenotazione(Reservation $prenotazione){
        $prenotazione->setCancella(true);
//		$this->em->remove($prenotazione);
        $this->em->flush();
    }

    public function getPrenotazioniCancellate(){
        $qb = $this->em->createQueryBuilder();

        $today = new \DateTime();
        $today->format('Y-m-d');
        $result =$qb->select('r')
            ->from('BookManagerBundle:Reservation', 'r')
            ->where('r.dataPrenotazione >= ?1')
            ->andWhere('r.cancella = 1')
            ->orderBy('r.dataPrenotazione', 'ASC')
            ->addOrderBy('r.hour' , 'ASC')
            ->setParameter(1,$today)
            ->getQuery()->execute();
        return $result;
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
            $qb2 = $this->em->createQueryBuilder();
            $data = $subset[0];
            $result =$qb2->select('p')
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
<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 02/05/16
 * Time: 20.23
 */

namespace BookManagerBundle\Service;


use BookManagerBundle\Entity\ClosingDays;
use BookManagerBundle\Entity\Schedule;
use BookManagerBundle\Entity\OrariPreferenze;
use Doctrine\ORM\EntityManager;

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

        $savedSchedule =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->andWhere('s.days = ?2')
            ->setParameter(1,$sport)
            ->setParameter(2,$day)
            ->setMaxResults(1)
            ->getQuery()->execute();

        return $savedSchedule;
    }

    public function getSport($sport){
        return $this->em->getRepository('BookManagerBundle:Sport')->find($sport);
    }

    public function saveSchedule($sport, $day){
        $sport = $this->getSport($sport);

        $date = new \DateTime();
        $date->format('Y-m-d');
        $schedule = new Schedule();
        $schedule->setSport($sport);
        $schedule->setValidFrom($date);
        $schedule->setDays($day);
        $this->em->persist($schedule);
        $this->em->flush();

    }

    public function deleteSchedule($schedule){
        $this->em->createQuery(
            'DELETE BookManagerBundle:Schedule s
               WHERE s.id = :scheduledId')
            ->setParameter("scheduledId", $schedule[0]->getId())->execute();
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

    public function getClosingDays($today){
        $fields = array('c.date');
        $qb = $this->em->createQueryBuilder();

        $closingDays =$qb->select($fields)
            ->from('BookManagerBundle:ClosingDays', 'c')
            ->where('c.date > ?1')
            ->setParameter(1,$today)
            ->getQuery()->execute();

        return $closingDays;
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


}
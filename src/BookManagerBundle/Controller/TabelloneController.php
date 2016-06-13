<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 26/04/16
 * Time: 20.22
 */

namespace BookManagerBundle\Controller;


use BookManagerBundle\Entity\Schedule;
use BookManagerBundle\Entity\Sport;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\VarDumper\VarDumper;


/**
 * Tabellone controller.
 *
 * @Route("/admin/tab")
 */
class TabelloneController extends Controller{


    /**
     * List planning entities.
     *
     * @Route("/", name="tab_index")
     * @Method("GET")
     */
    public function indexAction(Request $request){

      //  $em = $this->getDoctrine()->getManager();

        $dbManager =    $this->get('app.dbmanager');

        $lun = array('name'=> 'lunedì', 'code'=>'lun', 'giorno_num' => 1);
        $mar = array('name'=> 'martedì', 'code'=>'mar', 'giorno_num' => 2);
        $mer = array('name'=> 'mercoledì', 'code'=>'mer', 'giorno_num' => 3);
        $gio = array('name'=> 'giovedì', 'code'=>'gio', 'giorno_num' => 4);
        $ven = array('name'=> 'venerdì', 'code'=>'ven', 'giorno_num' => 5);
        $sab = array('name'=> 'sabato', 'code'=>'sab', 'giorno_num' => 6);
        $dom = array('name'=> 'domenica', 'code'=>'dom', 'giorno_num' => 0);
        $box_giorni = array($lun, $mar, $mer, $gio, $ven, $sab, $dom);

        $schedules = $dbManager->getAllSchedules();

        $sports = $dbManager->getAllSports();

        return $this->render('tab/index.html.twig', array(
            'sports' => $sports,
            'schedules' => $schedules,
            'box_giorni' => $box_giorni
        ));
    }
    /**
     * Save planning.
     *
     * @Route("/save", name="tab_save")
     * @Method("POST")
     */
    public function saveAction(Request $request){
        $data = json_decode($request->getContent());
/*
        $qb = $this->get('doctrine.orm.default_entity_manager')->createQueryBuilder();

        $savedSchedule =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->andWhere('s.days = ?2')
            ->setParameter(1,$data->sport)
            ->setParameter(2,$data->day)
            ->setMaxResults(1)
            ->getQuery()->execute();
*/
        $dbManager =    $this->get('app.dbmanager');

        $savedScheduled = $dbManager->getSportScheduledForDay($data->sport, $data->giorno);
        $schedule = new Schedule();
        $schedule->setDaysNumber($data->giorno_numero);
        $schedule->setValidFrom($data->giorno);
        $schedule->setSport();

        if($data->checked){
            if(sizeof($savedScheduled) == 0){
                $dbManager->saveSchedule($data->sport, $data->day);

                /*
                $sport = $this->getDoctrine()
                    ->getRepository('BookManagerBundle:Sport')
                    ->find($data->sport);

                $date = new \DateTime();
                $date->format('Y-m-d');
                $schedule = new Schedule();
                $schedule->setSport($sport);
                $schedule->setValidFrom($date);
                $schedule->setDays($data->day);
                $em = $this->getDoctrine()->getManager();
                $em->persist($schedule);
                $em->flush();
                */
            }
        }else{

            $dbManager->deleteSchedule($savedScheduled);
            /*
            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery(
                'DELETE BookManagerBundle:Schedule s
               WHERE s.id = :scheduledId')
                ->setParameter("scheduledId", $savedSchedule[0]->getId());

            $query->execute();
*/

        }


        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
        //  var_dump($request);
        // $data = $request->request->get('data');
        // return $this->render('tab/save.html.twig', $data);
    }

}
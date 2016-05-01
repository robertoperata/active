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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;


/**
 * Tabellone controller.
 *
 * @Route("/tab")
 */
class TabelloneController extends Controller{


    /**
     * List planning entities.
     *
     * @Route("/", name="tab_index")
     * @Method("GET")
     */
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $sports = $em->getRepository('BookManagerBundle:Sport')->findAll();

        return $this->render('tab/index.html.twig', array(
            'sports' => $sports
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



        $qb = $this->get('doctrine.orm.default_entity_manager')->createQueryBuilder();
        $savedSchedule =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->andWhere('s.days = ?2')
            ->setParameter(1,$data->sport)
            ->setParameter(2,$data->day)
            ->getQuery()
            ->getResult();

        VarDumper::dump($savedSchedule);
        $sport = $savedSchedule->getSport();

        //TODO: add your logic here

        $date = new \DateTime();
        $date->format('Y-m-d');
        $schedule = new Schedule();
        $schedule->setSport($sport);
        $schedule->setValidFrom($date);
        $schedule->setDays($data->day);
        $em = $this->getDoctrine()->getManager();
        $em->persist($schedule);
        $em->flush();




        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      //  var_dump($request);
       // $data = $request->request->get('data');
       // return $this->render('tab/save.html.twig', $data);
    }

    /**
     * @Route("/test/{id}/{day}", name="tab_test")
     * @Method("GET")
     *
     * Doctrine sa da solo come idratare lo sport in base all'{id}
     */
    public function testQueryAction(Sport $sport, $day)
    {

        $qb = $this->get('doctrine.orm.default_entity_manager')->createQueryBuilder();
        $savedSchedule =$qb->select('s')
            ->from('BookManagerBundle:Schedule', 's')
            ->where('s.sport = ?1')
            ->andWhere('s.days = ?2')
            ->setParameter(1,$sport) //Puoi usare indifferentemente l'oggetto o il suo ->getId()
            ->setParameter(2,$day)
            ->getQuery()
            ->getResult();

        VarDumper::dump($savedSchedule);
        die();
    }
}

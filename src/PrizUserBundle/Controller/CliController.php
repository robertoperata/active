<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 29/05/16
 * Time: 9.07
 */

namespace PrizUserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BookManagerBundle\Entity\Sport;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * End USer controller.
 *
 * @Route("/cli")
 */
class CliController extends Controller{

    /**
     * Lista Sport
     *
     * @Route("/", name="frontend")
     * @Method("GET")
     */
    public function indexAction(){
        //recupera gli sport
        $dbManager =    $this->get('app.dbmanager');
        $sports = $dbManager->getAllSports();


        return $this->render('cli/index.html.twig', array(
            'sports' => $sports
        ));
    }

    /**
     * Lista giorni per sport
     *
     * @Route("/step2", name="step2")
     * @Method("POST")
     */
    public function step2(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);

        try{
            $sportEntity = $dbManager->getSport($data->id_sport);

            $daysPerSport = $dbManager->getDaysPerSport($sportEntity);
            $sport = array('id'=>$sportEntity->getId(), 'name'=>$sportEntity->getName(), 'minPlayer'=>$sportEntity->getMinPlayer(), 'maxPlayer'=>$sportEntity->getMaxPlayer());
            $schedule  = array();
            foreach($daysPerSport as $item){
                $temp = array("day"=>$item->getDays(), "day_number"=>$item->getDaysNumber());
                array_push($schedule, $temp);
            }
            $result = array("sport"=>$sport, "schedule"=>$schedule);
            $response->setContent(json_encode($result));
        }catch (Exception $e){
            $response->setStatusCode(400);
        }

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Salva prenotazione
     *
     * @Route("/save", name="saveReservation")
     * @Method("POST")
     */
    public function saveReservation(Request $request){

    }

    /**
     * CArica gli orari
     *
     * @Route("/loadOrari", name="loadOrari")
     * @Method("POST")
     */
    public function loadOrari(Request $request){
        $data = json_decode($request->getContent());
        $day = new \DateTime($data->day);
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode('200');
        try{
            $orariApertura = $dbManager->getOrariAperturaPerGriono($day);
            $response->setContent(json_encode($orariApertura));
        }catch (Exception $e){
            $response->setStatusCode('400');
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 29/05/16
 * Time: 9.07
 */

namespace PrizUserBundle\Controller;

use BookManagerBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BookManagerBundle\Entity\Sport;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @Route("/save", name="preCheckout")
     * @Method("POST")
     */
    public function preCheckout(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');

        $response = new Response();
        $response->setStatusCode('200');

        $data_prenotazione = new \DateTime($data->day);

        $sport =  $dbManager->getSport($data->sport);

        //caricare elenco di prenotazioni per sport giorno ora

        // controllo numero giocatori
        $players = $data->players;


        $errori = false;
        /*
        if($players < $sport->getMinPlayer()){
            $testo =  "I giocatori devono essere almeno ".$sport->getMinPlayer();
            $errori = true;
        }elseif( $giocatoriTotale > $sport->getMaxPlayer()){
            $errori = true;
            $testo =   "I giocatori non devono essere più di ".$sport->getMaxPlayer();
        }
        */
        if($errori){
            $response->setStatusCode('400');

            $response->setContent($testo);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $residentChk = false;
        if( $data->residenti === 'true' ){
            $residentChk = true;
        }


        $reservation = new Reservation();
        $reservation->setSport($sport);
        $reservation->setDataPrenotazione($data_prenotazione);
        $reservation->setDate(new \DateTime());
        $reservation->setPlayers($data->players);
        $reservation->setHour($data->hour);
        $reservation->setResidentChk($residentChk);

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reservation->setUser($user);
        $reservation->setName($user->getUsername());

        $session = $request->getSession();
        if(empty($session)){
            $session = new Session();
        }
        $session->start();
        $session->set('reservation', $reservation);

        $response = new Response();
        $response->setStatusCode('200');
        try{
            $orari = $dbManager->getTimePreferencies($data_prenotazione);
            $tariffaNotturna = false;
            $tariffaResidenti = 0;
            $tariffaNonResidenti = 0;
            if($data->hour >= $orari[0]->getNotturno()){
                 $totale = $sport->getPriceLightsOn();

                $tariffaNotturna  = true;
            }else{
                 $totale = $sport->getPriceLightsOn();

            }

            $importi = array(   'totale'=>$totale, );

           $response->setContent(json_encode($importi));
        }catch (Exception $e){
            $response->setStatusCode('400');
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * CArica gli orari
     *
     * @Route("/loadOrari", name="loadOrari")
     * @Method("POST")
     */
    public function loadOrari(Request $request){
        $data = json_decode($request->getContent());
        $day = new \DateTime($data->data_orari);
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

    /**
     * Salva prenotazione
     *
     * @Route("/checkout", name="checkout")
     * @Method("POST")
     */
    public function checkout(Request $request){
        $session = $request->getSession();

        $reservation = $session->get('reservation');

        $dbManager =    $this->get('app.dbmanager');

        $campo_id = $dbManager->getReservationPerSportAndDay($reservation->getSport(), $reservation->getDataPrenotazione(), $reservation->getHour());

        $numeroCampo = 1;
        if(sizeof($campo_id) > 0){
            $numeroCampo = sizeof($campo_id) + 1;
        }
        $reservation->setCampoId($numeroCampo);

        $response = new Response();
        $response->setStatusCode('200');
        try{
            $prenotazione = $dbManager->saveReservation($reservation);
            $response->setContent(json_encode($prenotazione));
        }catch (Exception $e){
            $response->setStatusCode('400');
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
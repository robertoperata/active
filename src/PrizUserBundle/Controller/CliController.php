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
use Symfony\Component\Validator\Constraints\Date;
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
     * @Route("/index", name="frontend")
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
     * Lista prenotazioni
     *
     * @Route("/", name="controlloPrenotazioni")
     * @Method("GET")
     */
    public function controlloPrenotazioni(){
        $dbManager =    $this->get('app.dbmanager');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //controlla prenotazioni utente
        $prenotazioni = $dbManager->getUserReservations($user->getId());
            if(sizeof($prenotazioni) > 0){
                return $this->render('cli/controlloPrenotazioni.html.twig', array(
                    'prenotazioni' => $prenotazioni
                ));
            }else{
                return $this->redirectToRoute('frontend');
            }
    	}

    /**
     * Cancella prenotazioni
     *
     * @Route("/cancellaPrenotazione", name="cancellaPrenotazioni")
     * @Method("POST")
     */
    public function cancellaPrenotazioni(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        $prenotazione = $dbManager->getReservationById($data->id);
        try{
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($prenotazione->getUser()->getId() == $user->getId()){
                $dbManager->cancellaPrenotazione($prenotazione);
                $obj = array('status'=>'ok');
                $response->setContent(json_encode($obj));
            }else{
                throw new \Exception('utente non valido');
            }

        }catch (Exception $e){
            $obj = array('status'=>'ko');
            $response->setContent(json_encode($obj));
            $response->setStatusCode(400);
        }
        //invio messaggio Email
        $email = $user->getEmail();
        $dataPrenotazione = new \DateTime($prenotazione->getDataPrenotazione()->format('Y-m-d')." ".$prenotazione->getHour().":00:00");

        $differenza = $dataPrenotazione->diff(new \DateTime());
        $dirittoBuono = false;
        if($differenza->d > 0){
            $dirittoBuono = true;

        }
        //mailCancellazione($prenotazione, $email, $dirittoBuono);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function mailCancellazione(Reservation $prenotazione, $mail, $dirittoBuono){
        if($dirittoBuono){
            $mailTemplate = 'email/cancellazioneConBuono.html.twig';
        }else{
            $mailTemplate = 'email/cancellazioneSenzaBuono.html.twig';

        }

        $message = \Swift_Message::newInstance()
            ->setSubject('Cancellazione')
            ->setFrom('info@letiziasportrelax.it')
            ->setTo($mail)
            ->setCc('info@letiziasportrelax.it')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    $mailTemplate,
                    array('prenotazione' => $prenotazione)
                ),
                'text/plain'
            );
        $this->get('mailer')->send($message);
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

            $giorniPerSport = [];
            $oggi = new \DateTime();


            for($i = 0; $i < 7; $i++){
                $valid_from = $dbManager->getDataValiditaPerGiorno($oggi, $i);
                $calendarioPerSport = $dbManager->getSportFromSchedule($valid_from, $i);
                $giorniPerSport[$i] = $calendarioPerSport;
            }
            $giorniAbilitati = [];
            for($i=0; $i < 60; $i++){
                $currentDate = new \DateTime();
                $currentDate->setTimezone(new \DateTimeZone('Europe/Rome'));
                $currentDate->format('Y-m-d');
                $currentDate->modify('+'.$i.' day');
                $currentDate;
                $numeroGiorno = $currentDate->format('w');
                $ultimaDataValida = null;
                for($m = 0; $m < sizeof($giorniPerSport[$numeroGiorno]); $m++){

                    if($currentDate->format('Y-m-d') >= $giorniPerSport[$numeroGiorno][$m]->getValidFrom()->format('Y-m-d')){
                        $ultimaDataValida = $giorniPerSport[$numeroGiorno][$m]->getValidFrom();

                    }
                }
                for($m = 0; $m < sizeof($giorniPerSport[$numeroGiorno]); $m++){

                    if($giorniPerSport[$numeroGiorno][$m]->getValidFrom()->format('Y-m-d') == $ultimaDataValida->format('Y-m-d')){

                        if( $giorniPerSport[$numeroGiorno][$m]->getSport()->getId() == $sportEntity->getId()){

                            $tmp = new \DateTime($currentDate->format('Y-m-d'), new \DateTimeZone('Europe/Rome'));
                            $giorniAbilitati[$tmp->getTimestamp()] = true;
                        }
                    }
                }
            }
            // cerchiamo i giorni di chiusura da oggi in aventi
            $today = new \DateTime();
            $today->format('Y-m-d');
            $closingDays = $dbManager->getClosingDays($today);

            //eliminiamo dai giorni disponibili i giorni di chiusura
            for($i = 0; $i < sizeof($closingDays); $i++){

               unset($giorniAbilitati[$closingDays[$i]['date']]);
            }

            $dataNormalizzata = [];
            $giorniAbilitati = array_keys($giorniAbilitati);
            for($i = 0; $i < sizeof($giorniAbilitati); $i++){
                $dtStr = date("c", $giorniAbilitati[$i]);
                $date = new \DateTime($dtStr);
                $dataNormalizzata[] = $date->format('Y-n-j');
            }


           // $dataTabelloneInCorso = $dbManager->getDataTabelloneInCorso();
            $daysPerSport = $dbManager->getDaysPerSport($sportEntity);
              $sport = array('id'=>$sportEntity->getId(), 'name'=>$sportEntity->getName());
//            $schedule = $sport->getSchedule();
//            $schedule  = array();
//            foreach($daysPerSport as $item){
//                $temp = array("day"=>$item->getDays(), "day_number"=>$item->getDaysNumber());
//                array_push($schedule, $temp);
//            }
            $result = array("sport"=>$sport, "giorniAbilitati"=>$dataNormalizzata);
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



        //caricare elenco di prenotazioni per sport giorno ora



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

           // $response->setContent($testo);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $sport = $dbManager->getSport($data->sport);
        $reservation = new Reservation();
        $user = $this->get('security.token_storage')->getToken()->getUser();
  //      $reservation->setUser($user);
        $reservation->setName($user->getUsername());
        $reservation->setCell($user->getCellNumber());
        $reservation->setDate(new \DateTime());
        $reservation->setSport($sport);
        $reservation->setHour($data->hour);
        $reservation->setDataPrenotazione($data_prenotazione);

        $session = $request->getSession();
        if(empty($session)){
            $session = new Session();
        }


        $response = new Response();
        $response->setStatusCode('200');
        try{
            $orari = $dbManager->getTimePreferencies($data_prenotazione);

            if($data->hour >= $orari[0]->getNotturno()){
                 $totale = $sport->getPriceLightsOn();
                $tariffaNotturna  = true;
            }else{
                 $totale = $sport->getPrice();

            }

            $importi = array(   'totale'=>$totale, );
            $session->start();
            $session->set('reservation', $reservation);
            $session->set('sportid', $data->sport);
            $session->set('prezzo', $totale);
            $session->set('descrizone', $totale);

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
            $sport = $dbManager->getSport($data->sport);

            //numero campi configurati per sport / giorno
            $sportScheudle = $dbManager->getSportScheduledForDay($sport, $day);

            //numero prenotazioni per sport / giorno raggruppate per ora
            // per determinare la disponiblità  di ulteriori campi
            $campiOccupati = $dbManager->getCampiDisponibiliPerSportEGiorno($sport, $day);
            $hourKey = array_column($campiOccupati, 'hour');
            $numeroCampi =  array_column($campiOccupati, 'num');
            $campiOccupati = array_combine($hourKey, $numeroCampi);

            $orariPrenotazione = [];
            for($i = $orariApertura[0]->getApertura(); $i < $orariApertura[0]->getChiusura(); $i++){
                $orariPrenotazione[$i] = true;
            }
            foreach($campiOccupati as $key=>$item){
                if($item == $sportScheudle[0]->getFieldsNumber()){
                    $orariPrenotazione[$key]=false;
                }
            }

            //chamare preontzioni per quel giorno con count su orari
            $result = array("orariPrenotazione"=>$orariPrenotazione);
            $response->setContent(json_encode($result));
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
        $sportid =  $session->get('sportid');

        $dbManager =    $this->get('app.dbmanager');

        $sport = $dbManager->getSport($sportid);
        $reservation->setSport($sport);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reservation->setUser($user);
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
<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 12/05/16
 * Time: 22.24
 */

namespace BookManagerBundle\Controller;

use BookManagerBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use BookManagerBundle\Entity\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Sport controller.
 *
 * @Route("/admin/prenotazioni")
 */
class PrenotazioniController extends Controller{
    /**
     * List planning entities.
     *
     * @Route("/", name="prenotazioni_index")
     * @Method("GET")
     */
    public function indexAction(){
        //recupera gli sport
        $dbManager =    $this->get('app.dbmanager');
        $sports = $dbManager->getAllSports();


        return $this->render('reservations/index.html.twig', array(
            'sports' => $sports
        ));
    }



    /**
     * @Route("/delRservation", name="del_reservation")
     * @Method("POST")
     */
    public function delReservation(Request $request){
        $data = json_decode($request->getContent());
        $dbManager = $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        $id = $data->id_prenotazione;
        try{
            $dbManager->deleteReservation($id);
            $obj = array('status'=>'ok');
            $response->setContent(json_encode($obj));
        }catch (Exception $e){
            $response->setStatusCode(400);
        }

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

    /**
     * @Route("/saveRservation", name="save_reservation")
     * @Method("POST")
     */
    public function saveReservation(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $dataEsecuzione = new \DateTime();

        $reservation = new Reservation();
        $giorno = implode("-",array_reverse(explode("-",$data->giorno)));
        $dataPrenotazioneCampo = new \DateTime($giorno);
        $sport = $dbManager->getSport($data->id_sport);

        $response = new Response();
        $response->setStatusCode(200);
        $idPrenotazione = 0;
        try{
            if(!$data->id_prenotazione){
                $prenotazioni = $dbManager->getReservationPerSportAndDay($sport, $dataPrenotazioneCampo,  $data->ora);
            }else{
                $idPrenotazione = $data->id_prenotazione;
            }


        }catch (Exception $e){
            $response->setStatusCode(400);
        }

        if($idPrenotazione){
            $reservation = $dbManager->getReservationById($idPrenotazione);
        }else{
            $campo_numero = 1;
            if(sizeof($prenotazioni) > 0){
                $campo_numero = sizeof($prenotazioni) + 1;
            }
            $reservation->setCampoId($campo_numero);
        }

        $reservation->setName($data->nome);
        $reservation->setCell($data->cell);
        $reservation->setNote($data->note);
        $reservation->setDate($dataEsecuzione);
        $reservation->setSport($sport);
        $reservation->setHour($data->ora);
        $reservation->setDataPrenotazione($dataPrenotazioneCampo);
        try{
            $id = $dbManager->saveReservation($reservation);
            $response->setContent($id);
        }catch (Exception $e){
            $response->setStatusCode(400);
            $response->setContent("ko");
        }

        $response->headers->set('Content-Type', 'text/plain');

        return $response;

    }

}



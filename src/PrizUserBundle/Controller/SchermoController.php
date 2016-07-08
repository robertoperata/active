<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 03/07/16
 * Time: 18.15
 */

namespace PrizUserBundle\Controller;

use BookManagerBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;


/**
 * End USer controller.
 *
 * @Route("/schermo")
 */
class SchermoController extends Controller{



    /**
     * Prenotazioni correnti per TV
     *
     * @Route("/", name="schermo")
     * @Method("GET")
     */
    public function schermoAction(Request $request){
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        try{
            $dataCorrente = new \DateTime();
            $hour = $dataCorrente->format('G');
            $dataCorrente->format('Y-m-d');
            $prenotazioniCorrenti = $dbManager->getPrenotazioniPerDayEHour($dataCorrente, $hour);
            $prenotazioniSuccessive = $dbManager->getPrenotazioniPerDayEHour($dataCorrente, $hour +1);

            $prenotaziniOraCorrente = [];
            $prenotaziniOraSuccessiva = [];
            if(sizeof($prenotazioniCorrenti) > 0){
                $prenotaziniOraCorrente = $this->prenotazioniToArray($prenotazioniCorrenti);
            }
            if(sizeof($prenotazioniSuccessive)>0){
                $prenotaziniOraSuccessiva =  $this->prenotazioniToArray($prenotazioniSuccessive);
            }

            /*
            $elencoPrenotazioni = array();
            if(sizeof($prenotazioni) > 0) {
                foreach ($prenotazioni as $item) {
                    $temp = array('id_sport' => $item->getSport()->getId(),
                        'campo' => $item->getCampoId(),
                        'hour' => $item->getHour(),
                        'name' => $item->getName(),
                        'cell' => $item->getCell(),
                        'id' => $item->getId(),
                        'note'=>$item->getNote()

                    );
                    array_push($elencoPrenotazioni, $temp);
                }
            }
            */
          //  $response->setContent(json_encode($elencoPrenotazioni));

        }catch (Exception $e){
            $response->setStatusCode(400);
        }
        return $this->render('schermo/index.html.twig', array('prenotaziniOraCorrente'=>$prenotaziniOraCorrente,'oraCcorrente'=>$hour, 'prenotaziniOraSuccessiva'=>$prenotaziniOraSuccessiva,'oraSuccessiva'=>$hour +1 ));
    }

    private function prenotazioniToArray( $prenotazioni){
        $elencoPrenotazioni = array();
        if(sizeof($prenotazioni) > 0) {
            foreach ($prenotazioni as $item) {
                $temp = array('id_sport' => $item->getSport()->getId(),
                    'sportAbbreviazione' =>  $item->getSport()->getAbbreviazione(),
                    'campo' => $item->getCampoId(),
                    'hour' => $item->getHour(),
                    'name' => $item->getName(),
                    'cell' => $item->getCell(),
                    'id' => $item->getId(),
                    'note'=>$item->getNote()

                );
                array_push($elencoPrenotazioni, $temp);
            }
        }
        return $elencoPrenotazioni;
    }

    /**
     * Prenotazioni correnti per TV
     *
     * @Route("/schermoAjax", name="schermoAjax")
     * @Method("POST")
     */
    public function schermoAjaxAction(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        try{
            $dataCorrente = new \DateTime();
            $hour = $dataCorrente->format('G');
            $prenotazioni = $dbManager->getPrenotazioniPerDayEHour($dataCorrente->format('Y-m-d'), $hour - 1);
            $elencoPrenotazioni = array();
            if(sizeof($prenotazioni) > 0) {
                foreach ($prenotazioni as $item) {
                    $temp = array('id_sport' => $item->getSport()->getId(),
                        'campo' => $item->getCampoId(),
                        'hour' => $item->getHour(),
                        'name' => $item->getName(),
                        'cell' => $item->getCell(),
                        'id' => $item->getId(),
                        'note'=>$item->getNote()

                    );
                    array_push($elencoPrenotazioni, $temp);
                }
            }
            $response->setContent(json_encode($elencoPrenotazioni));

        }catch (Exception $e){
            $response->setStatusCode(400);
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}
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
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;


/**
 * Tabellone controller.
 *
 * @Route("/admin/tab")
 */
class TabelloneController extends Controller{


    private function getBoxGiorni(){
        $dom = array('name'=> 'domenica', 'code'=>'DOM', 'giorno_num' => 0);
        $lun = array('name'=> 'lunedì', 'code'=>'LUN', 'giorno_num' => 1);
        $mar = array('name'=> 'martedì', 'code'=>'MAR', 'giorno_num' => 2);
        $mer = array('name'=> 'mercoledì', 'code'=>'MER', 'giorno_num' => 3);
        $gio = array('name'=> 'giovedì', 'code'=>'GIO', 'giorno_num' => 4);
        $ven = array('name'=> 'venerdì', 'code'=>'VEN', 'giorno_num' => 5);
        $sab = array('name'=> 'sabato', 'code'=>'SAB', 'giorno_num' => 6);
        $box_giorni = array($dom, $lun, $mar, $mer, $gio, $ven, $sab);
        return $box_giorni;
    }


    /**
     * List planning entities.
     *
     * @Route("/", name="tab_index")
     * @Method("GET")
     */
    public function indexAction(Request $request){

      //  $em = $this->getDoctrine()->getManager();

        $dbManager =    $this->get('app.dbmanager');

        $box_giorni = $this->getBoxGiorni();

        $schedules = $dbManager->getAllSchedules();

        $sports = $dbManager->getAllSports();

        return $this->render('tab/index.html.twig', array(
            'sports' => $sports,
            'schedules' => $schedules,
            'box_giorni' => $box_giorni
        ));
    }

    /**
     * Ottiene la programmazione in corso e le future basate sul day_number.
     *
     * @Route("/loadPreferenze", name="tab_load_preferenze")
     * @Method("POST")
     */
    public function load_preferenze(Request $request){
        $data = json_decode($request->getContent());

        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);

        try{
            $tabellonePreferenze = $dbManager->getPrenotazioniTabellone($data->day_number);
            $elencoPrenotazioni = array();
            if(sizeof($tabellonePreferenze) > 0) {
                foreach ($tabellonePreferenze as $preferenza) {
                    $sport = ['id_sport'=>$preferenza->getSport()->getId(), 'nome'=>$preferenza->getSport()->getName()];
                    $temp = array('id_schedule' => $preferenza->getId(),
                        'sport' => $sport,
                        'day' => $preferenza->getDays(),
                        'valid_from' => $preferenza->getValidFrom(),
                        'day_number' => $preferenza->getDaysNumber(),
                        'fields' => $preferenza->getFieldsNumber()
                    );
                    array_push($elencoPrenotazioni, $temp);
                }
            }

            $response->setContent(json_encode($elencoPrenotazioni));
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Save planning.
     *
     * @Route("/save", name="tab_save")
     * @Method("POST")
     */
    public function saveAction(Request $request){
        $data = json_decode($request->getContent());

        $dbManager =    $this->get('app.dbmanager');
        $response = new Response(json_encode($data));

        $box_giorni = $this->getBoxGiorni();

     //   $savedScheduled = $dbManager->getSportScheduledForDay($data->sport, $data->giorno);
        $elencoSport = $data->sport;
        $ids = [];
        try{
            $response->setStatusCode(200);
            for($i = 0; $i < sizeof($elencoSport); $i++){
                $schedule = new Schedule();
                $validFrom = new \DateTime($data->giorno);
                $validFrom->format('Y-m-d');
                $schedule->setDaysNumber($data->giorno_numero);
                $schedule->setValidFrom($validFrom);
                $schedule->setDays($box_giorni[$data->giorno_numero]['code']);
                $sport = $dbManager->getSport($elencoSport[$i]->sport_id);
                $schedule->setSport($sport);
                $schedule->setFieldsNumber($elencoSport[$i]->fields);
                $id = $dbManager->saveSchedule($schedule);
                array_push($ids, $id);
            }
             $response->setContent(json_encode($ids));
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Save planning.
     *
     * @Route("/del", name="tab_delete")
     * @Method("POST")
     */
    function deleteTab(Request $request){
        $data = json_decode($request->getContent());

        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        try{
            $dbManager->deleteSchedule($data->id);
            $response->setContent("200");
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }

}
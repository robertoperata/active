<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 04/05/16
 * Time: 21.56
 */

namespace BookManagerBundle\Controller;
use BookManagerBundle\Entity\OrariPreferenze;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Dashboard controller.
 *
 * @Route("/admin/dashboard")
 */
class DashboardController extends Controller{

    /**
     * List planning entities.
     *
     * @Route("/", name="dash_index")
     * @Method("GET")
     */
    public function indexAction(){
        $calendarManager = $this->get('app.calendar');
        $defaultHolidays = $calendarManager->createDefaultHolidayCalendar();

        $dbManager =    $this->get('app.dbmanager');

        $today = new \DateTime();
        $today->format('Y-m-d');
        $closingDays = $dbManager->getClosingDays($today);

        if(sizeof($closingDays)){
                $defaultHolidays = $calendarManager->createClosingCalendar($defaultHolidays, $closingDays);
        }

        $timePreferencies = $dbManager->getTimePreferencies($today);

        $orariApertura = $dbManager->getOrariApertura();

    //    $tabellaSport = $this->tabellaSport($today);



        return $this->render('dashboard/index.html.twig', array(
            'defaultHolidays' => $defaultHolidays,
            'timePreferencies' => $timePreferencies,
            'orariApertura' => $orariApertura,
           // 'tabellaSport'=>json_encode($tabellaSport)
        ));

    }

    //TODO orari di diefault in file di configurazione
    private function getOrariDefault(){
        $orariDefault = array('apertura'=>'9', 'chiusura'=>'21', 'notturno'=>'19');
        return $orariDefault;
    }

    public function tabellaSport(\DateTime $date){
        //prendere orari apertura per quel giorno
        $dbManager =    $this->get('app.dbmanager');
        $orariApertura = $dbManager->getOrariAperturaPerGriono($date);
        if(empty($orariApertura)){
            $orari = $this->getOrariDefault();
        }else{

            $orari = $orariApertura[0];
        }
        $sportPerGiorno = $dbManager->getAllSportsForDay($date);
        $elencoSport = array();

        foreach( $sportPerGiorno as $sport ){

            $temp = array('name'=>$sport->getSport()->getName(),
                          'sport_id'=>$sport->getSport()->getId(),
                          'abbreziazione'=>$sport->getSport()->getAbbreviazione(),
                          'priceResident'=>$sport->getSport()->getPriceResident(),
                          'priceResidentLightsOn'=>$sport->getSport()->getPriceResidentLightsOn(),
                          'priceNotResident'=>$sport->getSport()->getPriceNotResident(),
                          'priceNotResidentLightsOn'=>$sport->getSport()->getPriceNotResidentLightsOn(),
                          'fieldsNumber'=>$sport->getSport()->getFieldsNumber(),
                          'sportColor'=>$sport->getSport()->getSportColor()
                );
           array_push($elencoSport, $temp);
        }

        $tabellaSport = array('giorno'=>$date, 'apertura'=>$orari->getApertura(), 'chiusura'=>$orari->getChiusura(), 'notturno'=>$orari->getNotturno(), 'sport'=>$elencoSport);

        return $tabellaSport;
    }

    /**
     *  Chiamata ajax che restituisce json con orari validi per il giorno chiamato, sportm numero di campi
     *
     * @Route("/loadTabellaSport", name="dash_loadTabellaSport")
     * @Method("POST")
     */
    public function getSportsPerGiorno(Request $request){
        $data = json_decode($request->getContent());
        $day = new \DateTime($data->day);
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode('200');
        try{
            $tabellaSport = $this->tabellaSport($day);
            $response->setContent(json_encode($tabellaSport));
        }catch (Exception $e){
            $response->setStatusCode('400');
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *  Chiamata ajax che restituisce json con prenotazioni per giorno chiamato
     *
     * @Route("/getReservations", name="dash_getReservation")
     * @Method("POST")
     */
    public function dash_getReservation(Request $request){
        $data = json_decode($request->getContent());
        $day = new \DateTime($data->day);
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode('200');
        try{
            $prenotazioni = $dbManager->getPrenotazioniPerDay($day);
            $elencoPrenotazioni = array();
            if(sizeof($prenotazioni) > 0) {
                foreach ($prenotazioni as $sport) {
                    $temp = array('id_sport' => $sport->getSportId()->getId(),
                        'campo' => $sport->getCampoId(),
                        'hour' => $sport->getHour(),
                        'name' => $sport->getName(),
                        'cell' => $sport->getCell(),
                        'resident_nr' => $sport->getResidentsNum(),
                        'not_resident_nr' => $sport->getNotResidentNum(),
                    );
                }
            }
                array_push($elencoPrenotazioni, $temp);
                $response->setContent(json_encode($elencoPrenotazioni));
        }catch (Exception $e){
            $response->setStatusCode('400');
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *
     * @Route("/saveCal", name="cal_save")
     * @Method("POST")
     */
    public function saveCalendar(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode('200');
        try{

        if($data->checked){
            $dbManager->addClosingDay($data->day);

        }else{
            $dbManager->deleteClosedDay($data->day);

        }
        }catch(\Exception $e){
            $response->setStatusCode('400');
        }


        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * List planning entities.
     *
     * @Route("/saveChiusura", name="chiusura_save")
     * @Method("POST")
     */
    public function addClosingDay(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        try{


            $dbManager->addClosingDay($data->dataChiusura);

        }catch (\Exception $e){
         $response->setStatusCode(200);

        }
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * List planning entities.
     *
     * @Route("/delPref", name="pref_delete")
     * @Method("POST")
     */
    public function deletePreferenze(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        try{

            $dbManager->deletePreference($data->id);

        }catch  (\Exception $e){
            $response->setStatusCode(400);

        }
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }



    /**
     * List planning entities.
     *
     * @Route("/savePref", name="pref_save")
     * @Method("POST")
     */
    public function savePreferenze(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');
        $response = new Response();
        $response->setStatusCode(200);
        try{

        $preferenza = new OrariPreferenze();
        $day = new \DateTime(implode("-", array_reverse(explode("/", $data->date))));
        $day->format('Y-m-d');
        $preferenza->setDate($day);
        $preferenza->setApertura($data->aper);
        $preferenza->setChiusura($data->chiu);
        $preferenza->setNotturno($data->nott);
        $dbManager->savePreferenza($preferenza);
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }


        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }



}
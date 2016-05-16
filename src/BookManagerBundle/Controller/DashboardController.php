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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sport controller.
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

        return $this->render('dashboard/index.html.twig', array(
            'defaultHolidays' => $defaultHolidays,
            'timePreferencies' => $timePreferencies
        ));

    }

    /**
     * List planning entities.
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

        $dbManager->addClosingDay($data->dataChiusura);

        $response = new Response();
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

        $dbManager->deletePreference($data->id);


        $response = new Response();
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
        $preferenza = new OrariPreferenze();
        $day = new \DateTime(implode("-", array_reverse(explode("/", $data->date))));
        $day->format('Y-m-d');
        $preferenza->setDate($day);
        $preferenza->setApertura($data->aper);
        $preferenza->setChiusura($data->chiu);
        $preferenza->setNotturno($data->nott);
        $dbManager->savePreferenza($preferenza);


        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }



}
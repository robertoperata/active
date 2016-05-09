<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 04/05/16
 * Time: 21.56
 */

namespace BookManagerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sport controller.
 *
 * @Route("/dashboard")
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

        return $this->render('dashboard/index.html.twig', array(
            'defaultHolidays' => $defaultHolidays,
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
        if($data->checked){
            $dbManager->addClosingDay($data->day);

        }else{
            $dbManager->deleteClosedDay($data->day);

        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }



}
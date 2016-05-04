<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 04/05/16
 * Time: 21.56
 */

namespace BookManagerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $default = $this->getDefaultClosingDays();
        $dbManager =    $this->get('app.dbmanager');

        $today = new \DateTime();
        $today->format('Y-m-d');
        $closingDays = $dbManager->getAllClosingDaysAfterToday($today);

    }

    private function getDefaultClosingDays(){

    }

}
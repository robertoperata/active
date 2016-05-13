<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 12/05/16
 * Time: 22.24
 */

namespace BookManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use BookManagerBundle\Entity\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/loadRservations", name="load_reservation")
     * @Method("POST")
     */
    public function getDaysPerScheduledSport(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');


        $sportEntity = $dbManager->getSport($data->id_sport);
        $daysPerSport = $dbManager->getDaysPerSport($sportEntity);
        $prenotazioni = $dbManager->getPrenotazioniPerSport($sportEntity);

        //creare json con chiave giorno e dentro prenotazioni
        return  null;
    }


}



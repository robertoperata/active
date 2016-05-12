<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 12/05/16
 * Time: 22.24
 */

namespace BookManagerBundle\Controller;


use BookManagerBundle\Entity\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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


        return $this->render('dashboard/index.html.twig', array(
            'sports' => $sports
        ));
    }

    public function getDaysPerScheduledSport(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');

        $sport = new Sport();
        $sportEntity = $dbManager->getSport($sport);
        $daysPerSport = $dbManager->getDaysPerSport($sportEntity);
        $prenotazioni = $dbManager->getPrenotazioniPerSport($sportEntity);

        //creare json con chiave giorno e dentro prenotazioni
        return  null;
    }


}



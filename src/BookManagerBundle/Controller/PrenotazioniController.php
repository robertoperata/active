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

        // ottengo in quali giorni è programmato questo sport
        $daysPerSport = $dbManager->getDaysPerSport($sportEntity);

        // ottengo le prenotazioni esistenti
        $prenotazioni = $dbManager->getPrenotazioniPerSport($sportEntity);

       // $json = buildTabelloneDisponibile($sportEntity, $daysPerSport, $prenotazioni);
        $json = '{
	"giorni": [{
		"day": "1,3",
		"parteDa":"2016-5-9"
	}],
	"book": [{
		"giorno": "2016-5-11",
		"h": "2",
		"nome": "xxx"
	},
	 {
		"giorno": "2016-5-11",
		"h": "4",
		"nome": "hhhh"
	}]
}';



        $response = new Response($json);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/saveRservation", name="save_reservation")
     * @Method("POST")
     */
    public function saveReservation(Request $request){
        $data = json_decode($request->getContent());
        $dbManager =    $this->get('app.dbmanager');

        $reservation = new Reservation();
        $giorno = implode("-",array_reverse(explode("-",$data->giorno)));
        $giorno_reverse = new \DateTime($giorno);
        $sport = $dbManager->getSport($data->id_sport);

        $reservation->setName($data->nome);
        $reservation->setDate($giorno_reverse);
        $reservation->setSportId($sport);
        $reservation->setHour($data->ora);
        $dbManager->saveReservation($reservation);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    private function buildTabelloneDisponibile($sportEntity, $daysPerSport, $prenotazioni){

        $orariApertura = 8;
        $json = "{'giorni':['day': '1,3'],'book':['date':'2016-05-16','h':'2', 'nome':'xxx'],'book':['date':'2016-05-16','h':'4', 'nome':'hhhh']}";
        return $json;
    }


}



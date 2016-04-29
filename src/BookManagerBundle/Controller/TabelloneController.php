<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 26/04/16
 * Time: 20.22
 */

namespace BookManagerBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Tabellone controller.
 *
 * @Route("/tab")
 */
class TabelloneController extends Controller{


    /**
     * List planning entities.
     *
     * @Route("/", name="tab_index")
     * @Method("GET")
     */
    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $sports = $em->getRepository('BookManagerBundle:Sport')->findAll();

        return $this->render('tab/index.html.twig', array(
            'sports' => $sports
        ));
    }
    /**
     * Save planning.
     *
     * @Route("/save", name="tab_save")
     * @Method("POST")
     */
    public function saveAction(Request $request){
        $data = json_decode($request->getContent());


        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
      //  var_dump($request);
       // $data = $request->request->get('data');
       // return $this->render('tab/save.html.twig', $data);
    }

}
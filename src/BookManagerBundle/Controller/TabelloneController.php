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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Tabellone controller.
 *
 * @Route("/tab")
 */
class TabelloneController extends Controller{


    /**
     * Lists all Sport entities.
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

}
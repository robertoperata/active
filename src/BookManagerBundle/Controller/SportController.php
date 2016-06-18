<?php

namespace BookManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BookManagerBundle\Entity\Sport;
use BookManagerBundle\Form\SportType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sport controller.
 *
 * @Route("/admin/sport")
 */
class SportController extends Controller
{
    /**
     * Lists all Sport entities.
     *
     * @Route("/", name="sport_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sports = $em->getRepository('BookManagerBundle:Sport')->findAll();

        $sport = new Sport();
        $form = $this->createForm('BookManagerBundle\Form\SportType', $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            return $this->redirectToRoute('sport_show', array('id' => $sport->getId()));
        }

        return $this->render('sport/index.html.twig', array(
            'sports' => $sports,
            'sport' => $sport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Sport entity.
     *
     * @Route("/new", name="sport_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $response = new Response();
        $response->setStatusCode(200);

        $sport = new Sport();
        $sport->setName($data->sport_name);
        $sport->setAbbreviazione($data->sport_abbr);
        $sport->setPrice($data->price);
        $sport->setPriceLightsOn($data->price);
        $sport->setSportColor($data->sportColor);

        $dbManager =    $this->get('app.dbmanager');
        $id = null;
        try{
            $id = $dbManager->saveSport($sport);
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }

        $data->id = $id;
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Finds and displays a Sport entity.
     * @Route(name="sport_show")
     * @Method("POST")
     */
    public function showAction(Sport $sport)
    {
        $deleteForm = $this->createDeleteForm($sport);

        return $this->render('sport/show.html.twig', array(
            'sport' => $sport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sport entity.
     *
     * @Route("/editSport" ,name="sport_edit")
     * @Method("POST")
     */
    public function editAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $response = new Response();
        $response->setStatusCode(200);

        $em = $this->getDoctrine()->getManager();

        $sport =  $em->getRepository('BookManagerBundle:Sport')->find($data->sport_id);
        $sport->setName($data->sport_name);
        $sport->setAbbreviazione($data->sport_abbr);
        $sport->setPrice($data->price);
        $sport->setPriceLightsOn($data->priceLightsOn);
        $sport->setSportColor($data->sportColor);

        try{

            $em->flush();
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }


        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * Deletes a Sport entity.
     *
     * @Route("/del", name="sport_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $response = new Response();
        $response->setStatusCode(200);
        $id = null;
        try{
            $sport =  $em->getRepository('BookManagerBundle:Sport')->find($data->sport_id);
            $em->remove($sport);
            $em->flush();
        }catch (\Exception $e){
            $response->setStatusCode(400);
        }

        $response = new Response(json_encode($id));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * Creates a form to delete a Sport entity.
     *
     * @param Sport $sport The Sport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sport $sport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sport_delete', array('id' => $sport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

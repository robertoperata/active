<?php

namespace BookManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BookManagerBundle\Entity\Sport;
use BookManagerBundle\Form\SportType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Sport controller.
 *
 * @Route("/sport")
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
        $sport = new Sport();
        $form = $this->createForm('BookManagerBundle\Form\SportType', $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            return $this->redirectToRoute('sport_show', array('id' => $sport->getId()));
        }

        return $this->render('sport/new.html.twig', array(
            'sport' => $sport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sport entity.
     *
     * @Route("/{id}", name="sport_show")
     * @Method("GET")
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
     * @Route("/{id}/edit", name="sport_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Sport $sport)
    {
        $deleteForm = $this->createDeleteForm($sport);
        $editForm = $this->createForm('BookManagerBundle\Form\SportType', $sport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sport);
            $em->flush();

            return $this->redirectToRoute('sport_edit', array('id' => $sport->getId()));
        }

        return $this->render('sport/edit.html.twig', array(
            'sport' => $sport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Sport entity.
     *
     * @Route("/{id}", name="sport_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Sport $sport)
    {
        $form = $this->createDeleteForm($sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sport);
            $em->flush();
        }

        return $this->redirectToRoute('sport_index');
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

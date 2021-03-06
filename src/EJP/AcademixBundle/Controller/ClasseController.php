<?php

namespace EJP\AcademixBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use EJP\AcademixBundle\Entity\Classe;
use EJP\AcademixBundle\Entity\Eleve;
use EJP\AcademixBundle\Entity\Etude;
use EJP\AcademixBundle\Form\ClasseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Classe controller.
 *
 * @Route("classe")
 */
class ClasseController extends Controller
{
    /**
     * Lists all classe entities.
     *
     * @Route("/", name="classe_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $classe = new Classe();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ClasseType::class, $classe);

        $classes = $em->getRepository('EJPAcademixBundle:Classe')->findAll();

        return $this->render('classe/index.html.twig', array(
            'classes' => $classes,
            'form' => $form->createView(),
        ));
    }


    /**
     * Lists all classe entities.
     *
     * @Route("/list", name="classe_index_list")
     * @Method("GET")
     */

    public function listAction()
    {

        $classe = new Classe();

        $em = $this->getDoctrine()->getManager();

        $classes = $em->getRepository('EJPAcademixBundle:Classe')->findAll();

        return $this->render('classe/select.html.twig', array(
            'classes' => $classes
        ));
    }

    /**
     * Creates a new classe entity.
     *
     * @Route("/new", name="classe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $classe = new Classe();
        $form = $this->createForm('EJP\AcademixBundle\Form\ClasseType', $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();

            return $this->redirectToRoute('classe_show', array('id' => $classe->getId()));
        }

        return $this->render('classe/new.html.twig', array(
            'classe' => $classe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a classe entity.
     *
     * @Route("/{id}", name="classe_show")
     * @Method("GET")
     */
    public function showAction(Classe $classe)
    {
        $form = $this->createForm('EJP\AcademixBundle\Form\ClasseType', $classe);

        return $this->render('classe/show.html.twig', array(
            'classe' => $classe,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/affectation", name="classe_affectation")
     * @Method("POST")
     */
    public function affectationAction(Request $request, Classe $classe)
    {

        $editForm = $this->createForm('EJP\AcademixBundle\Form\ClasseType', $classe);

        return $this->render('classe/affectation.html.twig', array(
            'form' => $editForm->createView(),
            'classe' => $classe
        ));
    }


    /**
     * Displays a form to edit an existing classe entity.
     *
     * @Route("/{id}/edit", name="classe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Classe $classe)
    {

        $originalEnseignes= new ArrayCollection();

        // Create an ArrayCollection of the current Parent objects in the database
        foreach ($classe->getEnseignes() as $en) {
            $originalEnseignes->add($en);
        }


        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($classe);
        $editForm = $this->createForm('EJP\AcademixBundle\Form\ClasseType', $classe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            foreach ($originalEnseignes as $en) {
                if (false === $classe->getEnseignes()->contains($en)) {

                    $em->remove($en);
                }
            }

            $em->flush();

            return $this->redirectToRoute('classe_show', array('id' => $classe->getId()));
        }

        return $this->render('classe/edit.html.twig', array(
            'classe' => $classe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a classe entity.
     *
     * @Route("/{id}", name="classe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Classe $classe)
    {
        $form = $this->createDeleteForm($classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($classe);
            $em->flush();
        }

        return $this->redirectToRoute('classe_index');
    }

    /**
     * Creates a form to delete a classe entity.
     *
     * @param Classe $classe The classe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Classe $classe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('classe_delete', array('id' => $classe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

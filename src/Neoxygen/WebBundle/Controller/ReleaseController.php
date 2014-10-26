<?php

namespace Neoxygen\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Neoxygen\WebBundle\Entity\Release;
use Neoxygen\WebBundle\Form\ReleaseType;

/**
 * Release controller.
 *
 * @Route("/releases")
 */
class ReleaseController extends Controller
{

    /**
     * Lists all Release entities.
     *
     * @Route("/", name="releases")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NeoxygenWebBundle:Release')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Release entity.
     *
     * @Route("/", name="releases_create")
     * @Method("POST")
     * @Template("NeoxygenWebBundle:Release:new.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $entity = new Release();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('releases_show', array('id' => $entity->getId(), 'slug' => $entity->getSlug())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Release entity.
     *
     * @param Release $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Release $entity)
    {
        $form = $this->createForm(new ReleaseType(), $entity, array(
            'action' => $this->generateUrl('releases_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Release entity.
     *
     * @Route("/new", name="releases_new")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        $entity = new Release();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Release entity.
     *
     * @Route("/{id}/{slug}", name="releases_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NeoxygenWebBundle:Release')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Release entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Release entity.
     *
     * @Route("/{id}/edit", name="releases_edit")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NeoxygenWebBundle:Release')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Release entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Release entity.
    *
    * @param Release $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Release $entity)
    {
        $form = $this->createForm(new ReleaseType(), $entity, array(
            'action' => $this->generateUrl('releases_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Release entity.
     *
     * @Route("/{id}", name="releases_update")
     * @Method("PUT")
     * @Template("NeoxygenWebBundle:Release:edit.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NeoxygenWebBundle:Release')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Release entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('releases_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Release entity.
     *
     * @Route("/{id}", name="releases_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NeoxygenWebBundle:Release')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Release entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('releases'));
    }

    /**
     * Creates a form to delete a Release entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('releases_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

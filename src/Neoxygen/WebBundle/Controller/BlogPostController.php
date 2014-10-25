<?php

namespace Neoxygen\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Neoxygen\WebBundle\Entity\BlogPost;
use Neoxygen\WebBundle\Form\BlogPostType;

/**
 * BlogPost controller.
 *
 * @Route("/news")
 */
class BlogPostController extends Controller
{

    /**
     * Lists all BlogPost entities.
     *
     * @Route("/", name="news")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NeoxygenWebBundle:BlogPost')->findAll();

        return array(
            'posts' => $entities,
        );
    }
    /**
     * Creates a new BlogPost entity.
     *
     * @Route("/", name="news_create")
     * @Method("POST")
     * @Template("NeoxygenWebBundle:BlogPost:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BlogPost();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('news_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a BlogPost entity.
     *
     * @param BlogPost $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BlogPost $entity)
    {
        $form = $this->createForm(new BlogPostType(), $entity, array(
            'action' => $this->generateUrl('news_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BlogPost entity.
     *
     * @Route("/new", name="news_new")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        $entity = new BlogPost();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a BlogPost entity.
     *
     * @Route("/{id}", name="news_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NeoxygenWebBundle:BlogPost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogPost entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BlogPost entity.
     *
     * @Route("/{id}/edit", name="news_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NeoxygenWebBundle:BlogPost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogPost entity.');
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
    * Creates a form to edit a BlogPost entity.
    *
    * @param BlogPost $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(BlogPost $entity)
    {
        $form = $this->createForm(new BlogPostType(), $entity, array(
            'action' => $this->generateUrl('news_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing BlogPost entity.
     *
     * @Route("/{id}", name="news_update")
     * @Method("PUT")
     * @Template("NeoxygenWebBundle:BlogPost:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NeoxygenWebBundle:BlogPost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogPost entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('news_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a BlogPost entity.
     *
     * @Route("/{id}", name="news_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NeoxygenWebBundle:BlogPost')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BlogPost entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('news'));
    }

    /**
     * Creates a form to delete a BlogPost entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('news_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

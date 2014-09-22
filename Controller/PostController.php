<?php

namespace Manhattan\Bundle\PostsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Manhattan\Bundle\PostsBundle\Entity\Post;
use Manhattan\Bundle\PostsBundle\Entity\Image;
use Manhattan\Bundle\PostsBundle\Form\PostType;

/**
 * Post controller.
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('manhattan.posts.entity.manager')
            ->findBy(array(), array('publishDate' => 'DESC'));

        return $this->render('ManhattanPostsBundle:Post:index.html.twig', array(
            'entities' => $entities,
            'include_documents' => $this->container->getParameter('manhattan_posts.include_documents')
        ));
    }

    /**
     * Displays a form to create a new Post entity.
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $entity = $this->get('manhattan.posts.entity.post');
        $this->createdCreateForm($entity);

        return $this->render('ManhattanPostsBundle:Post:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Post entity.
     */
    public function createAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $entity = $this->get('manhattan.posts.entity.post');

        $form = $this->createdCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('console_news'));
        }

        return $this->render('ManhattanPostsBundle:Post:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Post entity.
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('manhattan.posts.entity.manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ManhattanPostsBundle:Post:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'include_documents' => $this->container->getParameter('manhattan_posts.include_documents')
        ));
    }

    /**
     * Edits an existing Post entity.
     */
    public function updateAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('manhattan.posts.entity.manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('console_news_edit', array('id' => $id)));
        }

        return $this->render('ManhattanPostsBundle:Post:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'include_documents' => $this->container->getParameter('manhattan_posts.include_documents')
        ));
    }

    /**
     * Deletes a Post entity.
     */
    public function deleteAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createDeleteForm($id);

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $this->get('manhattan.posts.entity.manager')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Post entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('console_news'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Create a form
     *
     * @param  Post $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createdCreateForm(Post $entity)
    {
        $type = $this->get('manhattan.posts.form.post.type');

        $form = $this->createForm($type, $entity, array(
            'action' => $this->generateUrl('console_news_create'),
            'method' => 'POST',
            'attr' => array(
                'role' => 'form'
            )
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Create',
            'attr' => array(
                'class' => 'btn btn-primary col-sm-offset-2',
                'formnovalidate' => 'formnovalidate'
            )
        ));

        return $form;
    }

    /**
     * Creates a form to edit a Post entity.
     *
     * @param Post $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Post $entity)
    {
        $type = $this->get('manhattan.posts.form.post.type');

        $form = $this->createForm($type, $entity, array(
            'action' => $this->generateUrl('console_news_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Update',
            'attr' => array(
                'class' => 'btn btn-primary col-sm-offset-2',
                'formnovalidate' => 'formnovalidate'
            )
        ));

        return $form;
    }
}

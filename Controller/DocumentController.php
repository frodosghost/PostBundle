<?php

namespace Manhattan\Bundle\PostsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Manhattan\Bundle\PostsBundle\Entity\Post;
use Manhattan\Bundle\PostsBundle\Entity\Attachment;
use Manhattan\Bundle\PostsBundle\Form\AttachmentType;

/**
 * Attachment controller.
 */
class DocumentController extends Controller
{
    /**
     * Finds and displays documents for a Post.
     */
    public function attachmentsAction(Request $request, $id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_attachments')) {
            throw new AccessDeniedHttpException('Attachment functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('ManhattanPostsBundle:Post')
            ->findOneByIdJoinAttachments($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $document = new Attachment();
        $document->addPost($post);
        $form  = $this->createForm(new AttachmentType(), $document);

        return $this->render('ManhattanPostsBundle:Document:documents.html.twig', array(
            'entity' => $post,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Photo entity.
     */
    public function createAction(Request $request, $id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_attachments')) {
            throw new AccessDeniedHttpException('Attachment functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('ManhattanPostsBundle:Post')->findOneById($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $document = new Attachment();
        $document->addPost($post);

        $form  = $this->createForm(new AttachmentType(), $document);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_news_documents', array('id' => $post->getId())));
        }

        return $this->render('ManhattanPostsBundle:Document:documents.html.twig', array(
            'entity' => $post,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Attachment entity.
     */
    public function editAction(Request $request, $id, $document_id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_attachments')) {
            throw new AccessDeniedHttpException('Attachment functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('ManhattanPostsBundle:Attachment')
            ->findOneByIdJoinPost($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Attachment entity.');
        }

        $editForm = $this->createForm(new AttachmentType(), $document);

        return $this->render('ManhattanPostsBundle:Document:edit.html.twig', array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Edits an existing Attachment entity.
     */
    public function updateAction(Request $request, $id, $document_id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_attachments')) {
            throw new AccessDeniedHttpException('Attachment functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('ManhattanPostsBundle:Attachment')
            ->findOneByIdJoinPost($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Attachment entity.');
        }

        $editForm = $this->createForm(new AttachmentType(), $document);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_news_document_edit', array('id' => $id, 'document_id' => $document_id)));
        }

        return $this->render('ManhattanPostsBundle:Document:edit.html.twig', array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Deletes a Post entity.
     */
    public function deleteAction(Request $request, $id, $document_id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_attachments')) {
            throw new AccessDeniedHttpException('Attachment functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ManhattanPostsBundle:Attachment')->find($document_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attachment entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('console_news_documents', array('id' => $id)));
    }

}

<?php

namespace Manhattan\Bundle\PostsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Manhattan\Bundle\PostsBundle\Entity\Post;
use Manhattan\Bundle\PostsBundle\Entity\Document;
use Manhattan\Bundle\PostsBundle\Form\DocumentType;

/**
 * Document controller.
 *
 * @Route("/console/news")
 */
class DocumentController extends Controller
{
    /**
     * Finds and displays documents for a Post.
     *
     * @Route("/{id}/documents", name="console_news_documents")
     */
    public function documentsAction(Request $request, $id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('ManhattanPostsBundle:Post')
            ->findOneByIdJoinDocuments($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $document = new Document();
        $document->addPost($post);
        $form  = $this->createForm(new DocumentType(), $document);

        return $this->render('ManhattanPostsBundle:Document:documents.html.twig', array(
            'entity' => $post,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Photo entity.
     *
     * @Route("/{id}/document/create", name="console_news_document_create")
     * @Method("POST")
     */
    public function createAction(Request $request, $id)
    {
        var_dump('$form->isValid()');
        exit;
        if (!$this->container->getParameter('manhattan_posts.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('ManhattanPostsBundle:Post')->findOneById($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $document = new Document();
        $document->addPost($post);

        $form  = $this->createForm(new DocumentType(), $document);
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
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/document/{document_id}/edit", name="console_news_document_edit")
     */
    public function editAction(Request $request, $id, $document_id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('ManhattanPostsBundle:Document')
            ->findOneByIdJoinPost($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        return $this->render('ManhattanPostsBundle:Document:edit.html.twig', array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Edits an existing Document entity.
     *
     * @Route("/{id}/document/{document_id}/update", name="console_news_document_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id, $document_id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('ManhattanPostsBundle:Document')
            ->findOneByIdJoinPost($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

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
     *
     * @Route("/{id}/document/{document_id}/delete", name="console_news_document_delete")
     */
    public function deleteAction(Request $request, $id, $document_id)
    {
        if (!$this->container->getParameter('manhattan_posts.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ManhattanPostsBundle:Document')->find($document_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('console_news_documents', array('id' => $id)));
    }

}
<?php

namespace AGB\Bundle\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use AGB\Bundle\NewsBundle\Entity\Post;
use AGB\Bundle\NewsBundle\Entity\Document;
use AGB\Bundle\NewsBundle\Form\DocumentType;

use JMS\SecurityExtraBundle\Annotation\Secure;

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
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function documentsAction($id)
    {
        if (!$this->container->getParameter('agb_news.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AGBNewsBundle:Post')
            ->findOneByIdJoinDocuments($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $document = new Document();
        $document->addPost($post);
        $form  = $this->createForm(new DocumentType(), $document);

        return array(
            'entity' => $post,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Photo entity.
     *
     * @Route("/{id}/document/create", name="console_news_document_create")
     * @Method("POST")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("AGBNewsBundle:Document:documents.html.twig")
     */
    public function createAction($id)
    {
        if (!$this->container->getParameter('agb_news.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        $em = $this->getDoctrine()->getEntityManager();

        $post = $em->getRepository('AGBNewsBundle:Post')->findOneById($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $document = new Document();
        $document->addPost($post);

        $request = $this->getRequest();
        $form  = $this->createForm(new DocumentType(), $document);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_news_documents', array('id' => $post->getId())));
        }

        return array(
            'entity' => $post,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/document/{document_id}/edit", name="console_news_document_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id, $document_id)
    {
        if (!$this->container->getParameter('agb_news.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        $em = $this->getDoctrine()->getEntityManager();

        $document = $em->getRepository('AGBNewsBundle:Document')
            ->findOneByIdJoinPost($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        return array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Document entity.
     *
     * @Route("/{id}/document/{document_id}/update", name="console_news_document_update")
     * @Method("POST")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("AGBNewsBundle:Document:edit.html.twig")
     */
    public function updateAction($id, $document_id)
    {
        if (!$this->container->getParameter('agb_news.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        $em = $this->getDoctrine()->getEntityManager();

        $document = $em->getRepository('AGBNewsBundle:Document')
            ->findOneByIdJoinPost($document_id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $document);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('console_news_document_edit', array('id' => $id, 'document_id' => $document_id)));
        }

        return array(
            'entity'      => $document,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}/document/{document_id}/delete", name="console_news_document_delete")
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id, $document_id)
    {
        if (!$this->container->getParameter('agb_news.include_documents')) {
            throw new AccessDeniedHttpException('Document functionality has not been enabled in the bundle.');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AGBNewsBundle:Document')->find($document_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('console_news_documents', array('id' => $id)));
    }

}

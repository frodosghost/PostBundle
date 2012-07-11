<?php

namespace AGB\Bundle\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Public News controller.
 *
 * @Route("/news")
 */
class PublicController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("/", name="news")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $query = $em->getRepository('AGBNewsBundle:Post')->getQueryJoinImageAndCategory();

        $paginated_entities = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 6);
        compact($paginated_entities);

        return array(
            'entities' => $paginated_entities,
        );
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{date}/{slug}", name="news_view")
     * @Template()
     */
    public function viewAction($date, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AGBNewsBundle:Post')->findOneByDateAndSlug($date, $slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        return array(
            'entity'      => $entity
        );
    }

}
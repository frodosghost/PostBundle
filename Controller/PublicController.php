<?php

namespace AGB\Bundle\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Public News controller.
 *
 */
class PublicController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("/news", name="news")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $now = new \DateTime();

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
     * @Route("/news/{date}/{slug}", name="news_view")
     * @Template()
     */
    public function viewAction($date, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AGBNewsBundle:Post')->findOneByDateAndSlug($date, $slug);

        if (!$entity) {
            $log = $this->get('logger');
            $log->err(sprintf('[404: Page Not Found]: Unable to find Content entity with URI: "%s"', $this->getRequest()->getUri()));
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $latest_news = $em->getRepository('AGBNewsBundle:Post')->getLatestNews(5);

        return array(
            'entity'      => $entity,
            'latest_news' => $latest_news
        );
    }

}
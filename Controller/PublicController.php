<?php

namespace Manhattan\Bundle\PostsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

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

        $query = $em->getRepository('ManhattanPostsBundle:Post')->getQueryJoinImageAndCategory();

        $paginated_entities = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 6);
        compact($paginated_entities);

        return array(
            'entities' => $paginated_entities,
            'category' => null
        );
    }

    /**
     * Displays RSS2.0 for News Feed
     *
     * @Route("/news/rss.xml", name="news_rss2")
     */
    public function rssAction()
    {
        $em = $this->getDoctrine()->getManager();

        $latest_news = $em->getRepository('ManhattanPostsBundle:Post')->getLatestNews(20);

        $content = $this->renderView('ManhattanPostsBundle:RSS:rss2.xml.twig', array(
            'information' => $this->container->getParameter('manhattan_posts.rss'),
            'latest_news' => $latest_news
        ));

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/rss+xml');

        return $response;
    }

    /**
     * Lists all Post entities that belong to a category
     *
     * @Route("/news/{category}", name="news_category")
     * @Template("ManhattanPostsBundle:Public:index.html.twig")
     */
    public function categoryAction($category)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $query = $em->getRepository('ManhattanPostsBundle:Post')->getQueryJoinCategory($category);

        $paginated_entities = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 6);
        compact($paginated_entities);

        return array(
            'entities' => $paginated_entities,
            'category' => $category
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

        $entity = $em->getRepository('ManhattanPostsBundle:Post')->findOneByDateAndSlug($date, $slug);

        if (!$entity) {
            $log = $this->get('logger');
            $log->err(sprintf('[404: Page Not Found]: Unable to find Content entity with URI: "%s"', $this->getRequest()->getUri()));
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $latest_news = $em->getRepository('ManhattanPostsBundle:Post')->getLatestNews(5);

        return array(
            'entity'      => $entity,
            'latest_news' => $latest_news
        );
    }

}
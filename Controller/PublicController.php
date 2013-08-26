<?php

namespace Manhattan\Bundle\PostsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Public News controller.
 */
class PublicController extends Controller
{
    /**
     * Lists all Post entities.
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $query = $em->getRepository('ManhattanPostsBundle:Post')->getQueryJoinImageAndCategory();

        $paginated_entities = $paginator->paginate($query, $request->query->get('page', 1), 6);
        compact($paginated_entities);

        return $this->render('ManhattanPostsBundle:Public:index.html.twig', array(
            'entities' => $paginated_entities,
            'category' => null,
            'include_documents' => $this->container->getParameter('manhattan_posts.include_documents')
        ));
    }

    /**
     * Displays RSS2.0 for News Feed
     */
    public function rssAction()
    {
        $em = $this->getDoctrine()->getManager();

        $latest_posts = $em->getRepository('ManhattanPostsBundle:Post')->findAllLatestPosts(20);

        $content = $this->renderView('ManhattanPostsBundle:RSS:rss2.xml.twig', array(
            'information'  => $this->container->getParameter('manhattan_posts.rss'),
            'latest_posts' => $latest_posts
        ));

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/rss+xml');

        return $response;
    }

    /**
     * Lists all Post entities that belong to a category
     */
    public function categoryAction(Request $request, $category)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $query = $em->getRepository('ManhattanPostsBundle:Post')->getQueryJoinCategory($category);

        $paginated_entities = $paginator->paginate($query, $request->query->get('page', 1), 6);
        compact($paginated_entities);

        return $this->render('ManhattanPostsBundle:Public:index.html.twig', array(
            'entities' => $paginated_entities,
            'category' => $category,
            'include_documents' => $this->container->getParameter('manhattan_posts.include_documents')
        ));
    }

    /**
     * Finds and displays a Post entity.
     */
    public function viewAction($date, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ManhattanPostsBundle:Post')->findOneByDateAndSlug($date, $slug);

        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Exception: 404 Page Not Found. Unable to find Content entity with URI: "%s"', $this->getRequest()->getUri()));
        }

        return $this->render('ManhattanPostsBundle:Public:view.html.twig', array(
            'entity' => $entity,
            'include_documents' => $this->container->getParameter('manhattan_posts.include_documents')
        ));
    }

    /**
     * Sends 404 to Page AtomLogger
     *
     * @param  string     $message  Error Message to be Loggers
     * @param  \Exception $previous Previous message made prior to 404
     * @return NotFoundHttpException
     */
    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        if ($this->has('atom.404.logger')) {
            $log = $this->get('atom.404.logger');
            $log->addRecord(400, $message, array('request' => $this->getRequest()->getUri()));
        }

        return new NotFoundHttpException($message, $previous);
    }

}

<?php

namespace AGB\Bundle\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Post controller.
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

        $entities = $em->getRepository('AGBNewsBundle:Post')->findAll();

        return array(
            'entities' => $entities,
        );
    }

}
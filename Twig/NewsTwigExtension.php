<?php

/*
 * This file is part of the Manhattan Web Bundle.
 */

namespace Manhattan\Bundle\PostsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Twig Extension for displaying latest news
 *
 * @author James Rickard <james@frodosghost.com>
 */
class NewsTwigExtension extends \Twig_Extension
{
    private $environment;

    private $template;

    private $twig_template;

    private $doctrine;

    /**
     * @param \Twig_Environment $environment
     * @param RegistryInterface $doctrine
     * @param string            $template
     */
    public function __construct(\Twig_Environment $environment, RegistryInterface $doctrine, $template)
    {
        $this->environment = $environment;
        $this->doctrine = $doctrine;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            'latest_news'   => new \Twig_Function_Method($this, 'renderLatest', array('is_safe' => array('html'))),
            'recent_list'   => new \Twig_Function_Method($this, 'renderRecent', array('is_safe' => array('html'))),
            'category_list' => new \Twig_Function_Method($this, 'renderCategories', array('is_safe' => array('html')))
        );
    }

    /**
     * Builds and returns Twig Template
     */
    public function getTemplate()
    {
    	if (!$this->twig_template instanceof \Twig_Template) {
            $this->twig_template = $this->environment->loadTemplate($this->template);
        }

        return $this->twig_template;
    }

    /**
     * Renders Latest News Items displaying excerpt below heading
     *
     * @param  integer $item_count
     * @param  array   $options
     * @return string
     */
    public function renderLatest($item_count, array $options = array())
    {
        $latest_news = $this->getDoctrine()->getRepository('ManhattanPostsBundle:Post')
            ->getLatestNews($item_count);

    	$html = $this->getTemplate()->renderBlock('latest_news', array(
        	'latest_news' => $latest_news,
        	'options'     => $options
        ));

        return $html;
    }

    /**
     * Renders recent News Items in list with published date
     *
     * @param  integer $item_count
     * @param  array   $options
     * @return string
     */
    public function renderRecent($item_count, array $options = array())
    {
        $recent_news = $this->getDoctrine()->getRepository('ManhattanPostsBundle:Post')
            ->getLatestNews($item_count);

        $html = $this->getTemplate()->renderBlock('recent_list', array(
            'recent_news' => $recent_news,
            'options'     => $options
        ));

        return $html;
    }

    /**
     * Renders category listings
     *
     * @param  array  $options
     * @return string
     */
    public function renderCategories(array $options = array())
    {
        $categories = $this->getDoctrine()->getRepository('ManhattanPostsBundle:Category')
            ->getCategoriesJoinPost();

        $html = $this->getTemplate()->renderBlock('categories', array(
            'categories' => $categories,
            'options'    => $options
        ));

        return $html;
    }

    /**
     * @return RegistryInterface
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manhattan_posts_twig';
    }
}

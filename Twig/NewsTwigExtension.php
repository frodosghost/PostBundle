<?php

/*
 * This file is part of the AGB Web Bundle.
 */

namespace AGB\Bundle\NewsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

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

    /**
     * @param \Twig_Environment $environment
     * @param string            $template
     */
    public function __construct(\Twig_Environment $environment, $template)
    {
        $this->environment = $environment;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            'latest_news' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html')))
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
     * Renders the whole Navbar with the specified renderer.
     *
     * @param  \Doctrine\Common\Collections\ArrayCollection|array $latest_news
     * @param  array                               				  $options
     * @return string
     */
    public function render($latest_news, array $options = array())
    {
    	$html = $this->getTemplate()->renderBlock('latest_news', array(
        	'latest_news' => $latest_news,
        	'options'     => $options
        ));

        return $html;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'agb_news_twig';
    }
}

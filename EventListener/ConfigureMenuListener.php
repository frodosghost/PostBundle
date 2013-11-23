<?php

namespace Manhattan\Bundle\PostsBundle\EventListener;

use Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    private $title;

    /**
     * @param Manhattan\Bundle\PostsBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $dropdown = $menu->addChild($this->getTitle(), array('route'=>''))
            ->setLabelAttribute('class', 'pure-menu-heading')
            ->setChildrenAttribute('class', 'pure-menu-children yellow');

        $dropdown->addChild($this->getTitle() .' Index', array('route' => 'console_news'));
        $dropdown->addChild('New Post', array('route' => 'console_news_new'));
        $dropdown->addChild('Category Index', array('route' => 'console_news_category'));
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return (isset($this->title)) ? $this->title : 'News';
    }
}

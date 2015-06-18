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

        // Main Menu Item
        $dropdown = $menu->addChild($this->getTitle(), array(
            'route' => 'console_news',
            'icon' => 'file',
            'inverted' => false,
            'append' => false,
            'dropdown' => true,
            'caret' => true
        ));
        $dropdown->addChild($this->getTitle() .' Index', array('route' => 'console_news'));
        $dropdown->addChild('New Post', array('route' => 'console_news_new'));
        $dropdown->addChild('divider_1', array('divider' => true));
        $dropdown->addChild('Category Index', array('route' => 'console_news_category'));
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return (isset($this->title)) ? $this->title : 'Posts';
    }
}

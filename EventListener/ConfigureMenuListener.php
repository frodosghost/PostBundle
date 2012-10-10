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

        $dropdown = $menu->addChild($this->getTitle(), array('route'=>'console_news'))
            ->setLinkattribute('class', 'dropdown-toggle')
            ->setLinkattribute('data-toggle', 'dropdown')
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'menu-dropdown');

        $dropdown->addChild($this->getTitle(), array('route' => 'console_news'))
            ->setLinkattribute('class', 'main');
        $dropdown->addChild('New Post', array('route' => 'console_news_new'));
        $dropdown->addChild('Category', array('route' => 'console_news_category'))
            ->setLinkattribute('class', 'main');
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

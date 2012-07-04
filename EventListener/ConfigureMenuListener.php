<?php

namespace AGB\Bundle\NewsBundle\EventListener;

use Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param AGB\Bundle\ConsoleBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $dropdown = $menu->addChild('News', array('route'=>'console_news'))
            ->setLinkattribute('class', 'dropdown-toggle')
            ->setLinkattribute('data-toggle', 'dropdown')
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'menu-dropdown');

        $dropdown->addChild('News', array('route' => 'console_news'));
        $dropdown->addChild('New Post', array('route' => 'console_news_new'));

    }
}

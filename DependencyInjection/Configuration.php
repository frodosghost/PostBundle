<?php

namespace Manhattan\Bundle\PostsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('manhattan_posts');

        $rootNode
            ->children()
                ->arrayNode('rss')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('title')
                            ->defaultValue('News Title')
                            ->info('Sets the Title for the RSS Feed')
                            ->end()
                        ->scalarNode('link')
                            ->defaultValue('posts')
                            ->info('Route name to the index page of the news.')
                            ->end()
                        ->scalarNode('description')
                            ->defaultValue('News Description')
                            ->info('Sets the Description for the RSS Feed as displayed in <channel> tags')
                            ->end()
                        ->arrayNode('image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('title')
                                    ->defaultValue('Article Title')
                                    ->info('Sets the Title for the RSS Feed')
                                    ->end()
                                ->scalarNode('link')
                                    ->defaultValue('posts')
                                    ->info('Route name to the index page of the news.')
                                    ->end()
                                ->scalarNode('src')
                                    ->defaultValue('posts')
                                    ->info('Route name to the index page of the news.')
                                    ->end()
                            ->end()
                        ->end()
                        ->scalarNode('docs')
                            ->defaultValue('http://feed2.w3.org/docs/rss2.html')
                            ->info('Sets the Docs reference for the RSS Feed')
                            ->end()
                        ->scalarNode('category')
                            ->info('Sets Category reference for the RSS Feed')
                            ->defaultValue('All:All:All')
                            ->end()
                        ->scalarNode('copyright')
                            ->defaultValue('Copyright')
                            ->info('Sets Copyright information for the RSS Feed')
                            ->end()
                    ->end()
                ->end()

                ->arrayNode('configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')
                            ->defaultValue('Articles')
                            ->info('Sets the Title for the RSS Feed')
                            ->end()
                        ->booleanNode('include_attachments')
                            ->defaultValue(false)
                            ->info('If Document Management is required for use with the News Bundle.')
                            ->end()
                    ->end()
            ->end();

        return $treeBuilder;
    }
}

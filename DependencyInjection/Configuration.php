<?php

namespace Youmesoft\CallrBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('youmesoft_callr');

        $rootNode->children()
                    ->arrayNode('debug')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('enabled')->defaultFalse()->end()
                            ->enumNode('mode')
                                ->defaultValue('file')
                                ->values(['file', 'orm'])
                            ->end()
                            ->scalarNode('path')->defaultNull()->end()
                            ->scalarNode('manager')->defaultValue('default')->end()
                        ->end()
                    ->end()
                    ->scalarNode('sms_sender')->isRequired()->end()
                    ->enumNode('auth_type')
                        ->isRequired()
                        ->values(['login_password', 'api_key'])
                    ->end()
                    ->arrayNode('credentials')
                        ->isRequired()
                        ->children()
                            ->scalarNode('key')->defaultValue('')->end()
                            ->scalarNode('username')->defaultValue('')->end()
                            ->scalarNode('password')->defaultValue('')->end()
                        ->end()
                    ->end()
                 ->end();


        return $treeBuilder;
    }
}

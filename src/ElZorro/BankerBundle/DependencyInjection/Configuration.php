<?php

namespace ElZorro\BankerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('el_zorro_banker');
        $rootNode
            ->children()
                ->arrayNode('categories')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('filters')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('filter')->isRequired()->end()
                            ->scalarNode('category')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

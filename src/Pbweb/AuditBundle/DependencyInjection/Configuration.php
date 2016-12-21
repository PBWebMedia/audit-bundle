<?php

namespace Pbweb\AuditBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pbweb_audit');
        $rootNode
            ->children()
                ->booleanNode('load_default_event_appenders')->defaultTrue()->end()
                ->booleanNode('load_default_event_loggers')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}

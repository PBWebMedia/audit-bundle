<?php

namespace Pbweb\AuditBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('pbweb_audit');

        // For BC with symfony/config < 4.2
        if (method_exists($treeBuilder, 'getRootNode')) {
            /** @var ArrayNodeDefinition $rootNode */
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('pbweb_audit');
        }

        $rootNode
            ->children()
                ->booleanNode('load_default_event_appenders')->defaultTrue()->end()
                ->booleanNode('load_default_event_loggers')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}

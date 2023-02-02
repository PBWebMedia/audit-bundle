<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pbweb_audit');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('load_default_event_appenders')->defaultTrue()->end()
                ->booleanNode('load_default_event_loggers')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}

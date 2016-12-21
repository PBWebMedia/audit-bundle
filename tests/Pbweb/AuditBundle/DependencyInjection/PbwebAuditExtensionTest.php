<?php

namespace Tests\Pbweb\AuditBundle\DependencyInjection;

use Pbweb\AuditBundle\DependencyInjection\PbwebAuditExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class PbwebAuditExtensionTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class PbwebAuditExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $config = [[
            'load_default_event_appenders' => false,
            'load_default_event_loggers' => false,
        ]];

        $root = 'pbweb_audit.';
        $extension = new PbwebAuditExtension();
        $extension->load($config, $container = new ContainerBuilder());

        $this->assertFalse($container->has('pbweb_audit.event_appender.user'));
        $this->assertFalse($container->has('pbweb_audit.event_logger.psr'));

        foreach (array_keys($container->getDefinitions()) as $id) {
            $this->assertStringStartsWith($root, $id);
        }
        foreach (array_keys($container->getAliases()) as $id) {
            $this->assertStringStartsWith($root, $id);
        }
        foreach (array_keys($container->getParameterBag()->all()) as $id) {
            $this->assertStringStartsWith($root, $id);
        }
    }
}

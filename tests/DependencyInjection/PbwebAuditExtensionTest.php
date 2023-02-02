<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\DependencyInjection;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Pbweb\AuditBundle\DependencyInjection\PbwebAuditExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PbwebAuditExtensionTest extends MockeryTestCase
{
    public function testRoot(): void
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
            if ($id == 'service_container') {
                continue;
            }
            if (str_contains($id, '\\')) {
                $this->assertStringStartsWith('Pbweb\\AuditBundle\\', $id);

                continue;
            }
            $this->assertStringStartsWith($root, $id);
        }
        foreach (array_keys($container->getAliases()) as $id) {
            if (str_contains($id, '\\')) {
                continue;
            }
            $this->assertStringStartsWith($root, $id);
        }
        foreach (array_keys($container->getParameterBag()->all()) as $id) {
            $this->assertStringStartsWith($root, $id);
        }
    }
}

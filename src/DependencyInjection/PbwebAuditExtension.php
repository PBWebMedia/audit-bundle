<?php

namespace Pbweb\AuditBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PbwebAuditExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        if ($config['load_default_event_appenders']) {
            $loader->load('default/appenders.yml');
        }
        if ($config['load_default_event_loggers']) {
            $loader->load('default/loggers.yml');
        }

        $root = 'pbweb_audit.';
    }
}

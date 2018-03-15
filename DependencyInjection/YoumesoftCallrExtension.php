<?php

namespace Gatman\CallrBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class GatmanCallrExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if ($config['disable_delivery']) {
            $container->getDefinition('gatman_callr.manager')
                      ->replaceArgument(1, new Reference('gatman_callr.null_transporter'));
        }

        $container->getDefinition('gatman_callr.transporter')->replaceArgument(0, $config);
        $container->getDefinition('gatman_callr.subscriber')->replaceArgument(1, $config);

        $container->findDefinition('gatman_callr.data_collector')
                  ->addTag('data_collector', [
                      'template' => '@GatmanCallr/Collector/callr.html.twig',
                      'id'       => 'callr',
                      'priority' => 245,
                  ]);
    }
}

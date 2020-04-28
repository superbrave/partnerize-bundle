<?php

namespace Superbrave\PartnerizeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SuperbravePartnerizeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('partnerize.base_uri', $config['base_uri']);
        $container->setParameter('partnerize.tracking_uri', $config['tracking_uri']);
        $container->setParameter('partnerize.application_key', $config['application_key']);
        $container->setParameter('partnerize.user_api_key', $config['user_api_key']);
        $container->setParameter('partnerize.campaign_id', $config['campaign_id']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}

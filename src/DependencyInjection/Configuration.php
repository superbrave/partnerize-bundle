<?php

namespace Superbrave\PartnerizeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * $this is the class that validates and merges configuration from your app/config files.
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 *
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('superbrave_partnerize');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('superbrave_partnerize');
        }

        $rootNode
            ->children()
                ->scalarNode('base_uri')
                    ->info('The base uri to which the client sends requests.')
                    ->defaultValue('https://api.partnerize.com/')->end()
                ->scalarNode('tracking_uri')
                    ->info('The uri where sales are tracked.')
                    ->defaultValue('https://prf.hn/conversion/')->end()
                ->scalarNode('application_key')
                    ->info('The application key identifies the Network you are making requests against.')
                    ->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('user_api_key')
                    ->info('User specific API key.')
                    ->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('campaign_id')
                    ->info('Unique Campaign ID of the Merchant')
                    ->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}

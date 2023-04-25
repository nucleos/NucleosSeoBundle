<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nucleos_seo');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('encoding')->defaultValue('UTF-8')->end()
            ->end()
        ;

        $this->addPageSection($rootNode);
        $this->addSitemapSection($rootNode);
        $this->addCacheSection($rootNode);

        return $treeBuilder;
    }

    private function addPageSection(ArrayNodeDefinition $node): void
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $node
            ->children()
                ->arrayNode('page')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default')->defaultValue('nucleos_seo.page.default')->end()
                        ->arrayNode('head')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('attribute')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('metas')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('element')
                            ->prototype('array')
                                ->normalizeKeys(false)
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                        ->scalarNode('separator')->defaultValue(' - ')->end()
                        ->scalarNode('title')->defaultValue('')->end()
                        ->scalarNode('title_prefix')->defaultNull()->end()
                        ->scalarNode('title_suffix')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addCacheSection(ArrayNodeDefinition $node): void
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $node
            ->children()
                ->arrayNode('cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSitemapSection(ArrayNodeDefinition $node): void
    {
        /** @psalm-suppress UndefinedInterfaceMethod, UndefinedMethod */
        $node
            ->children()
                ->arrayNode('sitemap')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('static')
                        ->defaultValue([])
                        ->prototype('array')
                            ->children()
                                 ->scalarNode('url')->end()
                                 ->integerNode('priority')->defaultNull()->end()
                                 ->scalarNode('changefreq')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

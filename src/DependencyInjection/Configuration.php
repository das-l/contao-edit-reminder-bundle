<?php

declare(strict_types=1);

/*
 * This file is part of Contao Edit Reminder Bundle.
 *
 * (c) Das L – Alex Wuttke Software & Media
 *
 * @license LGPL-3.0-or-later
 */

namespace DasL\ContaoEditReminderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_edit_reminder');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->arrayNode('tables')
                    ->scalarPrototype()->end()
                    ->defaultValue(['tl_page', 'tl_article', 'tl_content'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

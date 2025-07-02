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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoEditReminderExtension extends Extension
{
    public function getAlias(): string
    {
        return 'contao_edit_reminder';
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration();
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config')))
            ->load('services.yaml')
        ;

        $container->setParameter('contao_edit_reminder.tables', $config['tables']);
    }
}

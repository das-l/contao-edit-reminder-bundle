<?php

declare(strict_types=1);

/*
 * This file is part of Contao Edit Reminder Bundle.
 *
 * (c) Das L – Alex Wuttke Software & Media
 *
 * @license LGPL-3.0-or-later
 */

namespace DasL\ContaoEditReminderBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DasL\ContaoEditReminderBundle\ContaoEditReminderBundle;
use DasL\ContaoLastEditorBundle\ContaoLastEditorBundle;
use Terminal42\NotificationCenterBundle\Terminal42NotificationCenterBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoEditReminderBundle::class)
                ->setLoadAfter([ContaoLastEditorBundle::class, Terminal42NotificationCenterBundle::class]),
        ];
    }
}

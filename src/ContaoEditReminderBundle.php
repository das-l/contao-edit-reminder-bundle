<?php

declare(strict_types=1);

/*
 * This file is part of Contao Edit Reminder Bundle.
 *
 * (c) Das L – Alex Wuttke Software & Media
 *
 * @license LGPL-3.0-or-later
 */

namespace DasL\ContaoEditReminderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoEditReminderBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}

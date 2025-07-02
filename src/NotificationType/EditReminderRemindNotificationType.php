<?php

declare(strict_types=1);

/*
 * This file is part of Contao Edit Reminder Bundle.
 *
 * (c) Das L – Alex Wuttke Software & Media
 *
 * @license LGPL-3.0-or-later
 */

namespace DasL\ContaoEditReminderBundle\NotificationType;

use Terminal42\NotificationCenterBundle\NotificationType\NotificationTypeInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\EmailTokenDefinition;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\TokenDefinitionFactoryInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\TextTokenDefinition;

class EditReminderRemindNotificationType implements NotificationTypeInterface
{
    public const NAME = 'editReminder_remind';

    public function __construct(private readonly TokenDefinitionFactoryInterface $factory)
    {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getTokenDefinitions(): array
    {
        return [
            $this->factory->create(TextTokenDefinition::class, 'item_id', 'editReminder.item_id'),
            $this->factory->create(TextTokenDefinition::class, 'last_edited', 'editReminder.last_edited'),
            $this->factory->create(TextTokenDefinition::class, 'last_sent', 'editReminder.last_sent'),
            $this->factory->create(TextTokenDefinition::class, 'user_id', 'editReminder.user_id'),
            $this->factory->create(EmailTokenDefinition::class, 'user_email', 'editReminder.user_email'),
        ];
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of Contao Edit Reminder Bundle.
 *
 * (c) Das L – Alex Wuttke Software & Media
 *
 * @license LGPL-3.0-or-later
 */

namespace DasL\ContaoEditReminderBundle\Cron;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\StringUtil;
use Contao\UserModel;
use Doctrine\DBAL\Connection;
use Terminal42\NotificationCenterBundle\NotificationCenter;

#[AsCronJob('minutely')]
class SendEditReminderCron
{
    public function __construct(
        private readonly Connection $db,
        private readonly NotificationCenter $notificationCenter,
        private readonly array $tables,
    ) {
    }

    public function __invoke(): void
    {
        // TODO: Explicit Exception if there are Reminder tables that aren't LastEditor tables
        $time = time();

        foreach ($this->tables as $table) {
            $sendableItems = $this->db->fetchAllAssociativeIndexed("
                SELECT id, tstamp, editReminder_sendAfter, editReminder_repeatAfter, editReminder_lastSentTstamp, editReminder_notification, lastEditor
                FROM $table
                WHERE editReminder_doRemind = '1'
                AND editReminder_sendAfter != ''
                AND editReminder_repeatAfter != ''
                AND lastEditor != '0'
            ");

            foreach ($sendableItems as $id => $values) {
                $sendAfter = StringUtil::deserialize($values['editReminder_sendAfter']);
                $repeatAfter = StringUtil::deserialize($values['editReminder_repeatAfter']);

                if (!isset($sendAfter['unit']) || !isset($repeatAfter['unit'])) {
                    continue;
                }

                $multipliers = [];
                foreach (
                    ['send' => $sendAfter['unit'], 'repeat' => $repeatAfter['unit']] as $multiplierType => $unit
                ) {
                    $multipliers[$multiplierType] = match ($unit) {
                        'min' => 60,
                        'h' => 60 * 60,
                        'd' => 24 * 60 * 60,
                        'wk' => 7 * 24 * 60 * 60,
                    };
                }
                if (
                    0 === (int) $values['editReminder_lastSentTstamp'] && $values['tstamp'] < $time - $sendAfter['value'] * $multipliers['send']
                    || $values['editReminder_lastSentTstamp'] > 0 && $values['editReminder_lastSentTstamp'] < $time - $repeatAfter['value'] * $multipliers['repeat']
                ) {
                    $user = UserModel::findById($values['lastEditor']);

                    if (null === $user || $user->disable) {
                        continue;
                    }

                    $this->notificationCenter->sendNotification($values['editReminder_notification'], [
                        'item_id' => $id,
                        'last_edited' => $values['tstamp'],
                        'last_sent' => $values['editReminder_lastSentTstamp'],
                        'user_id' => $values['lastEditor'],
                        'user_email' => $user->email,
                    ]);

                    $this->db->update($table, ['editReminder_lastSentTstamp' => $time], ['id' => $id]);
                }
            }
        }
    }
}

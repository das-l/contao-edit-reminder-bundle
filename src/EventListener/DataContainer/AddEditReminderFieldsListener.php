<?php

declare(strict_types=1);

/*
 * This file is part of Contao Edit Reminder Bundle.
 *
 * (c) Das L – Alex Wuttke Software & Media
 *
 * @license LGPL-3.0-or-later
 */

namespace DasL\ContaoEditReminderBundle\EventListener\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsHook('loadDataContainer')]
class AddEditReminderFieldsListener
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly array $tables,
    ) {
    }

    public function __invoke(string $table): void
    {
        if (!isset($GLOBALS['TL_DCA'][$table]) || !\in_array($table, $this->tables, true)) {
            return;
        }

        $GLOBALS['TL_LANG'][$table]['editReminder_legend'] = $this->translator->trans('MSC.editReminder_legend', [], 'contao_default');

        $GLOBALS['TL_DCA'][$table]['palettes']['__selector__'][] = 'editReminder_doRemind';
        $GLOBALS['TL_DCA'][$table]['subpalettes']['editReminder_doRemind'] = 'editReminder_sendAfter,editReminder_repeatAfter,editReminder_notification';

        $GLOBALS['TL_DCA'][$table]['fields']['editReminder_lastSentTstamp'] = [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ];
        $GLOBALS['TL_DCA'][$table]['fields']['editReminder_doRemind'] = [
            'label' => [
                $this->translator->trans('MSC.editReminder_doRemind.0', [], 'contao_default'),
                $this->translator->trans('MSC.editReminder_doRemind.1', [], 'contao_default'),
            ],
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => ['type' => 'boolean', 'default' => false],
        ];
        $GLOBALS['TL_DCA'][$table]['fields']['editReminder_sendAfter'] = [
            'label' => [
                $this->translator->trans('MSC.editReminder_sendAfter.0', [], 'contao_default'),
                $this->translator->trans('MSC.editReminder_sendAfter.1', [], 'contao_default'),
            ],
            'inputType' => 'inputUnit',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50 clr'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ];
        $GLOBALS['TL_DCA'][$table]['fields']['editReminder_repeatAfter'] = [
            'label' => [
                $this->translator->trans('MSC.editReminder_repeatAfter.0', [], 'contao_default'),
                $this->translator->trans('MSC.editReminder_repeatAfter.1', [], 'contao_default'),
            ],
            'inputType' => 'inputUnit',
            'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ];

        foreach (['min', 'h', 'd', 'wk'] as $unit) {
            $GLOBALS['TL_DCA'][$table]['fields']['editReminder_sendAfter']['options'][$unit]
            = $GLOBALS['TL_DCA'][$table]['fields']['editReminder_repeatAfter']['options'][$unit]
            = $this->translator->trans('MSC.editReminder_unit.'.$unit, [], 'contao_default');
        }
        $GLOBALS['TL_DCA'][$table]['fields']['editReminder_notification'] = [
            'label' => [
                $this->translator->trans('MSC.editReminder_notification.0', [], 'contao_default'),
                $this->translator->trans('MSC.editReminder_notification.1', [], 'contao_default'),
            ],
            'inputType' => 'select',
            'foreignKey' => 'tl_nc_notification.title',
            'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50 clr'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ];

        $pm = PaletteManipulator::create();
        $pm
            ->addLegend('editReminder_legend', null, PaletteManipulator::POSITION_APPEND, true)
            ->addField('editReminder_doRemind', 'editReminder_legend')
        ;
        $palettes = &$GLOBALS['TL_DCA'][$table]['palettes'];

        foreach (array_keys($palettes) as $palette) {
            if ('__selector__' === $palette) {
                continue;
            }

            $pm
                ->applyToPalette($palette, $table)
            ;
        }
    }
}

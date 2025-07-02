<?php

declare(strict_types=1);

use Contao\EasyCodingStandard\Set\SetList;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withSets([SetList::CONTAO])
    ->withPaths([
        __DIR__.'/contao',
        __DIR__.'/src',
    ])
    ->withSkip([
        MethodChainingIndentationFixer::class => [
            '*/DependencyInjection/Configuration.php',
        ],
    ])
    ->withConfiguredRule(HeaderCommentFixer::class, [
        'header' => "This file is part of Contao Edit Reminder Bundle.\n\n(c) Das L – Alex Wuttke Software & Media\n\n@license LGPL-3.0-or-later",
    ])
    ->withParallel()
    ->withSpacing(lineEnding: "\n")
    ->withCache(sys_get_temp_dir().'/ecs_default_cache')
;

<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude([
        'node_modules',
        'vendor',
    ])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->notPath([
        'wow_examples.php',
        'inc/class-tgm-plugin-activation.php',
    ]);

return (new Config())
    ->setRules([
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_align' => false,
        'phpdoc_no_access' => false,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_to_comment' => false,
        'statement_indentation' => false,
    ])
    ->setFinder($finder);

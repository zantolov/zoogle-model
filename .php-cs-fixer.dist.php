<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$basePath = __DIR__.'/../../';

$finder = (new Finder())
    ->in([
        './src',
        './tests',
    ]);

return (new Config())
    ->setCacheFile(__DIR__.'/.cache')
    ->setRiskyAllowed(true)
    ->setParallelConfig(ParallelConfigFactory::detect(
        processTimeout: 240,
    ))
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '&' => null,
                '|' => null,
            ],
        ],
        'no_superfluous_phpdoc_tags' => true,
        'self_static_accessor' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'phpdoc_separation' => false,
        'date_time_immutable' => true,
        'global_namespace_import' => [
            'import_classes' => false,
        ],
        'mb_str_functions' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'ordered_class_elements' => [
            'order' => ['use_trait', 'case', 'constant', 'property', 'construct', 'destruct', 'magic', 'phpunit', 'method'],
        ],
        'ordered_interfaces' => true,
        'php_unit_method_casing' => [
            'case' => 'snake_case',
        ],
        'php_unit_size_class' => false,
        'php_unit_internal_class' => false,
        'php_unit_strict' => [
            'assertions' => [
                'assertAttributeEquals',
                'assertAttributeNotEquals',
                //                    'assertEquals', // This will replace all assertEquals with assertSame that can affect array comparisons
                //                    'assertNotEquals', // Look above (eg. comparing properties of objects of the same class, like DTOs)
            ],
        ],
        'php_unit_data_provider_name' => false,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'self',
            'methods' => [
                'atLeastOnce' => 'this',
                'exactly' => 'this',
                'once' => 'this',
                'never' => 'this',
            ],
        ],
        'native_function_invocation' => ['exclude' => ['@all'], 'strict' => true, 'include' => [], 'scope' => 'all'],
        'native_constant_invocation' => false,
        'php_unit_data_provider_return_type' => false,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'single_line_empty_body' => false,
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'phpdoc_to_comment' => false,
        'phpdoc_to_return_type' => true,
        'single_line_throw' => false,
        'static_lambda' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'phpdoc',
                'return',
                'throw',
                'try',
            ],
        ],
        'phpdoc_no_alias_tag' => false,
    ])
    ->setFinder($finder);

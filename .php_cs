<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'bootstrap/cache',
        'public',
        'resources',
        'storage',
        'tests',
        'vendor',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PSR2' => true,
    'no_unused_imports' => true,
    'declare_strict_types' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'fully_qualified_strict_types' => true,
    'blank_line_after_opening_tag' => true,
    'not_operator_with_successor_space' => true,
    'blank_line_before_statement' => [
        'statements' => [
            'return', 'throw', 'try', 'exit', 'if', 'switch', 'yield'
        ],
    ],
    'cast_spaces' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'function_typehint_space' => true,
    'include' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,
    'no_empty_comment' => false,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => true,
    'phpdoc_indent' => true,
    'phpdoc_no_access' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_types' => true,
    'phpdoc_var_annotation_correct_order' => true,
    'phpdoc_to_comment' => true,
    'return_type_declaration' => true,
    'single_blank_line_before_namespace' => true,
    'trim_array_spaces' => true,
    'linebreak_after_opening_tag' => true,
    'multiline_whitespace_before_semicolons' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_short_echo_tag' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'ternary_operator_spaces' => true,
    'braces' => false,
];

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder)
    ->setUsingCache(false);

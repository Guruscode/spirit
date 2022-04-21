<?php

return (new PhpCsFixer\Config)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        // An empty line feed should precede a return statement.
        'blank_line_before_statement' => false,
        // Methods must be separated with one blank line.
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        // Replaces `dirname(__FILE__)` expression with equivalent `__DIR__` constant.
        'dir_constant' => true,
        // Replace core functions calls returning constants with the constants.
        'function_to_constant' => true,
        // Pre- or post-increment and decrement operators should be used if possible.
        'increment_style' => false,
        // Ensure there is no code on the same line as the PHP open tag.
        'linebreak_after_opening_tag' => true,
        // List (`array` destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.
        'list_syntax' => ['syntax' => 'short'],
        // Use `&&` and `||` logical operators instead of `and` and `or`.
        'logical_operators' => true,
        // Replaces `intval`, `floatval`, `doubleval`, `strval` and `boolval` function calls with according type casting operator.
        'modernize_types_casting' => true,
        // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
        'multiline_whitespace_before_semicolons' => true,
        // All instances created with new keyword must be followed by braces.
        'new_with_braces' => false,
        // Master functions shall be used instead of aliases.
        'no_alias_functions' => true,
        // There should be no blank lines before a namespace declaration.
        'no_blank_lines_before_namespace' => true,
        // Removes `@param`, `@return` and `@var` tags that don't provide any useful information.
        'no_superfluous_phpdoc_tags' => true,
        // Logical NOT operators (`!`) should have one trailing whitespace.
        'not_operator_with_successor_space' => true,
        // Adds or removes `?` before type declarations for parameters with a default `null` value.
        'nullable_type_declaration_for_default_null_value' => true,
        // All PHPUnit test classes should be marked as internal.
        'php_unit_internal_class' => false,
        // Adds a default `@coversNothing` annotation to PHPUnit test classes that have no `@covers*` annotation.
        'php_unit_test_class_requires_covers' => false,
        // All items of the given phpdoc tags must be either left-aligned or (by default) aligned vertically.
        'phpdoc_align' => false,
        // Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line.
        'phpdoc_separation' => false,
        // Class names should match the file name.
        'psr_autoloading' => true,
        // Simplify `if` control structures that return the boolean result of their condition.
        'simplified_if_return' => true,
        // There should be exactly one blank line before a namespace declaration.
        'single_blank_line_before_namespace' => false,
        // Single line comments should use double slashes `//` and not hash `#`.
        'single_line_comment_style' => true,
        // Increment and decrement operators should be used if possible.
        'standardize_increment' => false,
        // Use the Elvis operator `?:` where possible.
        'ternary_to_elvis_operator' => true,
        // Use `null` coalescing operator `??` where possible. Requires PHP >= 7.0.
        'ternary_to_null_coalescing' => true,
        // Write conditions in Yoda style (`true`), non-Yoda style (`['equal' => false, 'identical' => false, 'less_and_greater' => false]`) or ignore those conditions (`null`) based on configuration.
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/app/')
            ->in(__DIR__.'/config/')
            ->in(__DIR__.'/tests/')
    );

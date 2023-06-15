<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'config',
        'bootstrap',
        'storage',
        'vendor',
    ]);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    '@PSR1' => true,
    '@PSR2' => true,
    '@PSR12' => true,
    '@PER' => true,
    'php_unit_method_casing' => ['case' => 'snake_case'],
//    'php_unit_test_annotation' => ['style' => 'annotation'],
])
    ->setFinder($finder)
    ->setRiskyAllowed(true);

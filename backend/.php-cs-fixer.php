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
    'void_return' => true,
    'concat_space' => ['spacing' => 'one'],
])
    ->setFinder($finder)
    ->setRiskyAllowed(true);

<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/config',
        __DIR__.'/routes',
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

return (new PhpCsFixer\Config())
    ->setCacheFile(__DIR__.'/.php_cs.cache')
    ->setRules(['@PSR12' => true])
    ->setFinder($finder);

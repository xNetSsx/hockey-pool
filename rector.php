<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\AnnotationWithValueToAttributeRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // register single rule
    ->withRules([
        AnnotationWithValueToAttributeRector::class,
    ])
    ->withAttributesSets(
        phpunit: true,
    )
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
//        codingStyle: true,
    )
    ;

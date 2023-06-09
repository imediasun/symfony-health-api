<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;
use Webmozart\Glob\Glob;

return static function (Configuration $config): Configuration {
    return $config
        ->addPatternFilter(PatternFilter::fromString('/ext-.*/'))
        ->addNamedFilter(NamedFilter::fromString('beberlei/doctrineextensions'))
        ->addNamedFilter(NamedFilter::fromString('doctrine/doctrine-migrations-bundle'))
        ->addNamedFilter(NamedFilter::fromString('dukecity/command-scheduler-bundle'))
        ->addNamedFilter(NamedFilter::fromString('lexik/jwt-authentication-bundle'))
        ->addNamedFilter(NamedFilter::fromString('matthiasnoback/symfony-console-form'))
        ->addNamedFilter(NamedFilter::fromString('nelmio/cors-bundle'))
        ->addPatternFilter(PatternFilter::fromString('/symfony\/.*/'))
        ->addNamedFilter(NamedFilter::fromString('twig/extra-bundle'))
        ->setAdditionalFilesFor('icanhazstring/composer-unused', [
            __FILE__,
            ...Glob::glob(__DIR__ . '/config/*.php'),
        ]);
};

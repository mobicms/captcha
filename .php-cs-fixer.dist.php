<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR12'       => true,
            'strict_param' => true,
            'array_syntax' => ['syntax' => 'short'],
        ]
    )
    ->setFinder(
        (new Finder())
            ->in(__DIR__)
    );

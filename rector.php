<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\RemoveDataProviderParamKeysRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\NarrowIdenticalWithConsecutiveRector;
use Rector\PHPUnit\Set\PHPUnitSetList;


return RectorConfig::configure()
    ->withParallel(240)
    ->withPaths([
        './src',
        './tests',
    ])
    ->withRootFiles()
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
        phpunit: true,
    )
    ->withImportNames(
        importDocBlockNames: false,
        importShortClasses: false,
        removeUnusedImports: true,
    )
    ->withPHPStanConfigs([
        './phpstan.neon',
    ])
    ->withSets([
        PHPUnitSetList::PHPUNIT_110,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ])
    ->withSkip([
        ArrayKeyExistsTernaryThenValueToCoalescingRector::class,
        NarrowIdenticalWithConsecutiveRector::class,
        InlineConstructorDefaultToPropertyRector::class,
        NullToStrictStringFuncCallArgRector::class,
        FirstClassCallableRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        ReturnEarlyIfVariableRector::class,
        CatchExceptionNameMatchingTypeRector::class,
        // We have "php_unit_test_case_static_method_calls" php-cs-fixer rule to use self
        PreferPHPUnitThisCallRector::class,
        RemoveDataProviderParamKeysRector::class,
    ]);

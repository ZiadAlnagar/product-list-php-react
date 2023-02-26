<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/core',
        __DIR__ . '/database',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/index.php',
    ]);

    // rules to skip
    // $ecsConfig->skip(([]));

    // run and fix, one by one
    $ecsConfig->sets([
        SetList::SYMPLIFY,
        SetList::COMMON,
        SetList::SPACES,
        SetList::ARRAY,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::CONTROL_STRUCTURES,
        SetList::CLEAN_CODE,
        SetList::STRICT,
        SetList::PSR_12,
        SetList::PHPUNIT,
    ]);

    // change $array = array(); to $array = [];
    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);

    // add declare(strict_types=1); to all php files:
    $ecsConfig->rule(DeclareStrictTypesFixer::class);

    $ecsConfig->rule(BlankLineAfterStrictTypesFixer::class);

    // [default: PHP_EOL]; other options: "\n"
    $ecsConfig->lineEnding("\n");

    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [
        LineLengthFixer::LINE_LENGTH => 100,
    ]);
};

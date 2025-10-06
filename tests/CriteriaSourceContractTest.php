<?php

declare(strict_types=1);

use ComplexHeart\Domain\Criteria\Contracts\CriteriaSource;

test('CriteriaSource interface exists and is accessible', function () {
    expect(interface_exists(CriteriaSource::class))->toBeTrue();
});

test('CriteriaSource interface has all required methods', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);

    $expectedMethods = [
        'filterGroups',
        'orderType',
        'orderBy',
        'pageLimit',
        'pageOffset',
        'pageNumber',
    ];

    foreach ($expectedMethods as $method) {
        expect($reflection->hasMethod($method))
            ->toBeTrue("Method '{$method}' should exist in CriteriaSource interface");
    }
});

test('CriteriaSource methods have correct return types', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);

    $methodReturnTypes = [
        'filterGroups' => 'array',
        'orderType' => 'string',
        'orderBy' => 'string',
        'pageLimit' => 'int',
        'pageOffset' => 'int',
        'pageNumber' => 'int',
    ];

    foreach ($methodReturnTypes as $methodName => $expectedType) {
        $method = $reflection->getMethod($methodName);
        expect($method->hasReturnType())->toBeTrue("Method {$methodName} should have return type");

        $returnType = $method->getReturnType();
        expect($returnType)->not->toBeNull("Method {$methodName} return type should not be null");

        if ($returnType instanceof ReflectionNamedType) {
            expect($returnType->getName())->toBe($expectedType, "Method {$methodName} should return {$expectedType}");
        }
    }
});

test('CriteriaSource is an interface not a class', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);

    expect($reflection->isInterface())->toBeTrue()
        ->and($reflection->isTrait())->toBeFalse();
});

test('CriteriaSource interface has correct namespace', function () {
    expect(CriteriaSource::class)
        ->toBe('ComplexHeart\Domain\Criteria\Contracts\CriteriaSource');
});

test('CriteriaSource filterGroups method has correct docblock return type', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);
    $method = $reflection->getMethod('filterGroups');

    $docComment = $method->getDocComment();
    expect($docComment)->toContain('@return array<array<array<string, mixed>>>');
});

test('CriteriaSource orderType method documents valid values', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);
    $method = $reflection->getMethod('orderType');

    $docComment = $method->getDocComment();
    expect($docComment)->toContain('asc, desc, none or random');
});

test('CriteriaSource pageOffset method documents default behavior', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);
    $method = $reflection->getMethod('pageOffset');

    $docComment = $method->getDocComment();
    expect($docComment)->toContain('default should be 0');
});

test('CriteriaSource pageNumber method documents offset computation', function () {
    $reflection = new ReflectionClass(CriteriaSource::class);
    $method = $reflection->getMethod('pageNumber');

    $docComment = $method->getDocComment();
    expect($docComment)->toContain('used to compute the offset');
});

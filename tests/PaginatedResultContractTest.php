<?php

declare(strict_types=1);

use ComplexHeart\Domain\Criteria\Contracts\PaginatedResult;

test('PaginatedResult interface exists and is accessible', function () {
    expect(interface_exists(PaginatedResult::class))->toBeTrue();
});

test('PaginatedResult interface has all required methods', function () {
    $reflection = new ReflectionClass(PaginatedResult::class);

    $expectedMethods = [
        'items',
        'total',
        'perPage',
        'currentPage',
        'lastPage',
        'hasMorePages',
        'count',
        'isEmpty',
        'isNotEmpty',
    ];

    foreach ($expectedMethods as $method) {
        expect($reflection->hasMethod($method))
            ->toBeTrue("Method '{$method}' should exist in PaginatedResult interface");
    }
});

test('PaginatedResult methods have correct return types', function () {
    $reflection = new ReflectionClass(PaginatedResult::class);

    $methodReturnTypes = [
        'items' => 'array',
        'total' => 'int',
        'perPage' => 'int',
        'currentPage' => 'int',
        'lastPage' => 'int',
        'hasMorePages' => 'bool',
        'count' => 'int',
        'isEmpty' => 'bool',
        'isNotEmpty' => 'bool',
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

test('PaginatedResult is an interface not a class', function () {
    $reflection = new ReflectionClass(PaginatedResult::class);

    expect($reflection->isInterface())->toBeTrue()
        ->and($reflection->isTrait())->toBeFalse();
});

test('PaginatedResult interface has correct namespace', function () {
    expect(PaginatedResult::class)
        ->toBe('ComplexHeart\Domain\Criteria\Contracts\PaginatedResult');
});

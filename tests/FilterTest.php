<?php

declare(strict_types=1);

use ComplexHeart\Domain\Criteria\Filter;

test('Filter should be created with equal operator.', function () {
    $filter = Filter::equal('name', 'Vincent');

    expect($filter->field())->toBe('name')
    ->and($filter->operator()->value)->toBe('=')
    ->and($filter->value())->toBe('Vincent');
});

test('Filter should be created with not equal operator.', function () {
    $filter = Filter::notEqual('name', 'Vincent');

    expect($filter->field())->toBe('name')
        ->and($filter->operator()->value)->toBe('!=')
        ->and($filter->value())->toBe('Vincent');
});

test('Filter should be created with greater operator.', function () {
    $filter = Filter::greaterThan('stars', 5);

    expect($filter->field())->toBe('stars')
        ->and($filter->operator()->value)->toBe('>')
        ->and($filter->value())->toBe(5);
});

test('Filter should be created with greater or equal operator.', function () {
    $filter = Filter::greaterOrEqualThan('stars', 5);

    expect($filter->field())->toBe('stars')
        ->and($filter->operator()->value)->toBe('>=')
        ->and($filter->value())->toBe(5);
});

test('Filter should be created with less operator.', function () {
    $filter = Filter::lessThan('stars', 5);

    expect($filter->field())->toBe('stars')
        ->and($filter->operator()->value)->toBe('<')
        ->and($filter->value())->toBe(5);
});

test('Filter should be created with less or equal operator.', function () {
    $filter = Filter::lessOrEqualThan('stars', 5);

    expect($filter->field())->toBe('stars')
        ->and($filter->operator()->value)->toBe('<=')
        ->and($filter->value())->toBe(5);
});

test('Filter should be created with in operator.', function () {
    $filter = Filter::in('country', ['es', 'fr', 'pt']);

    expect($filter->field())->toBe('country')
        ->and($filter->operator()->value)->toBe('in')
        ->and($filter->value())->toBe(['es', 'fr', 'pt']);
});

test('Filter should be created with not in operator.', function () {
    $filter = Filter::in('country', ['es', 'fr', 'pt']);

    expect($filter->field())->toBe('country')
        ->and($filter->operator()->value)->toBe('in')
        ->and($filter->value())->toBe(['es', 'fr', 'pt']);
});

test('Filter should be created with like operator.', function () {
    $filter = Filter::like('bio', 'developer');

    expect($filter->field())->toBe('bio')
        ->and($filter->operator()->value)->toBe('like')
        ->and($filter->value())->toBe('developer');
});

test('Filter should be created with not like operator.', function () {
    $filter = Filter::notLike('bio', 'developer');

    expect($filter->field())->toBe('bio')
        ->and($filter->operator()->value)->toBe('not like')
        ->and($filter->value())->toBe('developer');
});

test('Filter should be created with contains operator.', function () {
    $filter = Filter::contains('bio', 'developer');

    expect($filter->field())->toBe('bio')
        ->and($filter->operator()->value)->toBe('contains')
        ->and($filter->value())->toBe('developer');
});

test('Filter should be created with not contains operator.', function () {
    $filter = Filter::notContains('bio', 'developer');

    expect($filter->field())->toBe('bio')
        ->and($filter->operator()->value)->toBe('not contains')
        ->and($filter->value())->toBe('developer');
});

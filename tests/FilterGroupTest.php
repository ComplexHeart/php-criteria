<?php

declare(strict_types=1);

use ComplexHeart\Domain\Criteria\Filter;
use ComplexHeart\Domain\Criteria\FilterGroup;
use ComplexHeart\Domain\Criteria\Operator;

test('FilterGroup should only accept Filter instances.', function () {
    expect(FilterGroup::create())
        ->toBeInstanceOf(FilterGroup::class)
        ->toHaveCount(0);
});

test('FilterGroup should be created from primitive array of values.', function () {
    expect(FilterGroup::createFromArray([['field', '=', 'value']]))
        ->toHaveCount(1);
});

test('FilterGroup should be created without duplicated filters.', function () {
    $filters = [
        ['field', '=', 'value'],
        ['field', '=', 'value'],
    ];

    $g = FilterGroup::createFromArray($filters)
        ->addFilter(Filter::create('field', Operator::EQUAL, 'value'))
        ->addFilter(Filter::create('name', Operator::EQUAL, 'Vega'));

    expect($g)
        ->toHaveCount(2);
});

test('FilterGroup should add new filter with fluent interface.', function () {
    $filters = FilterGroup::create()
        ->addFilterEqual('name', 'Vincent')
        ->addFilterNotEqual('surname', 'Winnfield')
        ->addFilterGreaterThan('money', 10000)
        ->addFilterGreaterOrEqualThan('age', 35)
        ->addFilterLessThan('cars', 2)
        ->addFilterLessOrEqualThan('houses', 2)
        ->addFilterLike('bio', 'pork lover')
        ->addFilterNotLike('bio', 'dog lover')
        ->addFilterContains('name', 'nce')
        ->addFilterNotContains('name', 'les')
        ->addFilterIn('boss', ['Marcellus', 'Mia'])
        ->addFilterNotIn('hates', ['Ringo', 'Yolanda']);

    expect($filters)->toHaveCount(12);
});

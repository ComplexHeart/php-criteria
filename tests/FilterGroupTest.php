<?php

declare(strict_types=1);

use ComplexHeart\Domain\Criteria\Filter;
use ComplexHeart\Domain\Criteria\FilterGroup;
use ComplexHeart\Domain\Criteria\Operator;

test('FilterGroup only accept Filter instances.', function () {
    expect(FilterGroup::create())
        ->toBeInstanceOf(FilterGroup::class)
        ->toHaveCount(0);
});

test('Create FilterGroup from primitive array of values', function () {
    expect(FilterGroup::createFromArray([['field', '=', 'value']]))
        ->toHaveCount(1);
});

test('Create FilterGroup without duplicate filters.', function () {
    $filters = [
        ['field', '=', 'value'],
        ['field', '=', 'value'],
    ];

    $g = FilterGroup::createFromArray($filters)
        ->addFilter(Filter::create('field', Operator::equal(), 'value'))
        ->addFilter(Filter::create('name', Operator::equal(), 'Vega'));

    expect($g)
        ->toHaveCount(2);
});

<?php

declare(strict_types=1);

namespace ComplexHeart\Test\Domain\Criteria;

use ComplexHeart\Domain\Criteria\Criteria;
use ComplexHeart\Domain\Criteria\FilterGroup;
use ComplexHeart\Domain\Criteria\Order;
use ComplexHeart\Domain\Criteria\Page;

test('Change complete and partially the criteria order parameter.', function () {
    $c = Criteria::createDefault()
        ->withOrder(Order::createDescBy('name'));

    expect($c->orderBy())->toBe('name');
    expect($c->orderType())->toBe('desc');
    expect($c->order()->isNone())->toBe(false);

    $c = $c->withOrder(Order::createAscBy('name'));
    expect($c->orderType())->toBe('asc');

    $c = $c->withOrderBy('surname');
    expect($c->orderBy())->toBe('surname');

    $c = $c->withOrderType(Order::TYPE_ASC);
    expect($c->orderType())->toBe('asc');

    expect($c->order())->toBeInstanceOf(Order::class);
});

test('Change complete and partially the criteria page parameter.', function () {
    $c = Criteria::createDefault()
        ->withPage(Page::create(100, 50));

    expect($c->pageLimit())->toBe(100);
    expect($c->pageOffset())->toBe(50);

    $c = $c->withPageLimit(42);
    expect($c->pageLimit())->toBe(42);

    $c = $c->withPageOffset(10);
    expect($c->pageOffset())->toBe(10);

    expect($c->page())->toBeInstanceOf(Page::class);
});

test('Change the complete filter object from criteria.', function () {
    $c = Criteria::createDefault()
        ->withFilters(FilterGroup::createFromArray([['field', '=', 'value']]));

    expect($c->filters())->toHaveCount(1);
});

test('Add filters to criteria object.', function () {
    $c = Criteria::createDefault()
        ->addFilterEqual('name', 'Vincent')
        ->addFilterNotEqual('surname', 'winnfield')
        ->addFilterGreaterThan('money', '10000')
        ->addFilterGreaterOrEqualThan('age', '35')
        ->addFilterLessThan('cars', '2')
        ->addFilterLessOrEqualThan('houses', '2')
        ->addFilterLike('favoriteMeal', 'pork')
        ->addFilterIn('boss', ['marcellus', 'mia'])
        ->addFilterNotIn('hates', ['ringo', 'yolanda']);

    expect($c->filters())->toHaveCount(9);
});

test('Criteria object is correctly serialized to string.', function () {
    $c = Criteria::createDefault()
        ->addFilterEqual('name', 'Vincent')
        ->addFilterGreaterOrEqualThan('age', '35')
        ->withPageLimit(100)
        ->withPageOffset(0)
        ->withOrderBy('name')
        ->withOrderType(Order::TYPE_ASC);

    expect($c->__toString())->toBe('name.=.Vincent+age.>=.35#name.asc#100.0');
});
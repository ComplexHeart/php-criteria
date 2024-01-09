<?php

declare(strict_types=1);

namespace ComplexHeart\Test\Domain\Criteria;

use ComplexHeart\Domain\Criteria\Contracts\CriteriaSource;
use ComplexHeart\Domain\Criteria\Criteria;
use ComplexHeart\Domain\Criteria\Errors\CriteriaError;
use ComplexHeart\Domain\Criteria\FilterGroup;
use ComplexHeart\Domain\Criteria\Order;
use ComplexHeart\Domain\Criteria\Page;

test('Criteria should be successfully created from source.', function () {
    $c = Criteria::fromSource(new class implements CriteriaSource {
        public function filterGroups(): array
        {
            return [
                [
                    ['field' => 'name', 'operator' => '=', 'value' => 'Jules'],
                    ['field' => 'surname', 'operator' => '=', 'value' => 'Winnfield'],
                ]
            ];
        }

        public function orderType(): string
        {
            return 'asc';
        }

        public function orderBy(): string
        {
            return 'name';
        }

        public function pageLimit(): int
        {
            return 25;
        }

        public function pageOffset(): int
        {
            return 0;
        }
    });

    expect($c->groups())->toHaveCount(1)
        ->and($c->groups()[0])->toHaveCount(2)
        ->and($c->orderBy())->toBe('name')
        ->and($c->orderType())->toBe('asc')
        ->and($c->pageLimit())->toBe(25)
        ->and($c->pageOffset())->toBe(0);
});

test('Criteria should throw exception for invalid filter groups.', function () {
    try {
        Criteria::default()->withFilterGroup(fn() => [1, 2, 3]);
    } catch (CriteriaError $e) {
        expect($e->violations())->toHaveCount(1);
    }
});

test('Criteria should change complete and partially the criteria order parameter.', function () {
    $c = Criteria::default()
        ->withOrder(Order::createDescBy('name'));

    expect($c->orderBy())->toBe('name')
        ->and($c->orderType())->toBe('desc')
        ->and($c->order()->isNone())->toBeFalse();

    $c = $c->withOrder(Order::createAscBy('name'));
    expect($c->orderType())->toBe('asc');

    $c = $c->withOrder(Order::random());
    expect($c->order()->isRandom())->toBeTrue();

    $c = $c->withOrderRandom();
    expect($c->order()->isRandom())->toBeTrue();

    $c = $c->withOrderBy('surname');
    expect($c->orderBy())->toBe('surname');

    $c = $c->withOrderType('asc');
    expect($c->orderType())->toBe('asc')
        ->and($c->order())->toBeInstanceOf(Order::class);
});

test('Criteria should change complete and partially the criteria page parameter.', function () {
    $c = Criteria::default()
        ->withPage(Page::create(100, 50));

    expect($c->pageLimit())->toBe(100)
        ->and($c->pageOffset())->toBe(50);

    $c = $c->withPageLimit(42);
    expect($c->pageLimit())->toBe(42);

    $c = $c->withPageOffset(10);
    expect($c->pageOffset())->toBe(10)
        ->and($c->page())->toBeInstanceOf(Page::class);
});

test('Criteria should change the complete filter groups.', function () {
    $c = Criteria::default()
        ->withFilterGroups([
            FilterGroup::createFromArray([['field', '=', 'one']])
        ])
        ->withFilterGroups([
            FilterGroup::createFromArray([['field', '=', 'two']]),
            FilterGroup::createFromArray([['field', '=', 'three']]),
            FilterGroup::createFromArray([['field', '=', 'four']]),
        ]);

    expect($c->groups())->toHaveCount(3);
});

test('Criteria should add or filter group to criteria object.', function () {
    $c = Criteria::default()
        ->withFilterGroup(fn(FilterGroup $g): FilterGroup => $g
            ->addFilterEqual('name', 'Vincent')
            ->addFilterEqual('status', 'deceased')
        )
        ->withFilterGroup(fn(FilterGroup $g): FilterGroup => $g
            ->addFilterEqual('name', 'Jules')
            ->addFilterEqual('deceased', 'alive')
        );

    $groups = $c->groups();

    expect($groups)->toHaveCount(2)
        ->and($groups[0])->toHaveCount(2)
        ->and($groups[1])->toHaveCount(2);
});

test('Criteria should be correctly serialized to string.', function () {
    $c = Criteria::default()
        ->withFilterGroup(fn(FilterGroup $group): FilterGroup => $group
            ->addFilterEqual('name', 'Vincent')
            ->addFilterGreaterOrEqualThan('age', '35')
        )
        ->withPageLimit(100)
        ->withPageOffset(0)
        ->withOrderBy('name')
        ->withOrderType('asc');

    expect($c->__toString())->toBe('name.=.Vincent+age.>=.35#name.asc#100.0');
});
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
            return 'ASC';
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

        public function pageNumber(): int
        {
            return 3;
        }
    });

    expect($c->groups())->toHaveCount(1)
        ->and($c->groups()[0])->toHaveCount(2)
        ->and($c->orderBy())->toBe('name')
        ->and($c->orderType())->toBe('ASC')
        ->and($c->pageLimit())->toBe(25)
        ->and($c->pageOffset())->toBe(50);
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
        ->withOrder(Order::desc('name'));

    expect($c->orderBy())->toBe('name')
        ->and($c->orderType())->toBe('DESC')
        ->and($c->order()->isNone())->toBeFalse();

    $c = $c->withOrder(Order::asc('name'));
    expect($c->orderType())->toBe('ASC');

    $c = $c->withOrder(Order::random());
    expect($c->order()->isRandom())->toBeTrue();

    $c = $c->withOrderRandom();
    expect($c->order()->isRandom())->toBeTrue();

    $c = $c->withOrderBy('surname');
    expect($c->orderBy())->toBe('surname');

    $c = $c->withOrderType('ASC');
    expect($c->orderType())->toBe('ASC');
});

test('Criteria should change complete and partially the criteria page parameter.', function () {
    $c = Criteria::default()
        ->withPage(Page::create(100, 100));

    expect($c->page()->limit())->toBe(100)
        ->and($c->page()->offset())->toBe(100);

    $c = $c->withPageLimit(42);
    expect($c->pageLimit())->toBe(42);

    $c = $c->withPageOffset(10);
    expect($c->pageOffset())->toBe(10);
});

test('Criteria should change the complete filter groups.', function () {
    $c = Criteria::default()
        ->withFilterGroups([
            FilterGroup::fromArray([['name', '=', 'Vicent']])
        ])
        ->withFilterGroups([
            FilterGroup::fromArray([['name', '=', 'Jules']]),
            FilterGroup::fromArray([['name', '=', 'Marcellus']]),
            FilterGroup::fromArray([['name', '=', 'Butch']]),
        ]);

    expect($c->groups())->toHaveCount(3);
});

test('Criteria should add or filter group to criteria object.', function () {
    $c = Criteria::default()
        ->withFilterGroup(FilterGroup::create()
            ->addFilterEqual('name', 'Vincent')
            ->addFilterEqual('status', 'deceased')
        )
        ->withFilterGroup(FilterGroup::create()
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
        ->withFilterGroup(FilterGroup::create()
            ->addFilterEqual('name', 'Vincent')
            ->addFilterGreaterOrEqualThan('age', '35')
        )
        ->withPageLimit(100)
        ->withPageOffset(0)
        ->withOrderBy('name')
        ->withOrderType('ASC');

    expect($c->__toString())->toBe('name.=.Vincent+age.>=.35#name ASC#100 0');
});

test('Criteria should configure limit and offset using page number.', function () {
    $c = Criteria::default()
        ->withPageNumber(3, 25);

    expect($c->pageLimit())->toBe(25)
        ->and($c->pageOffset())->toBe(50);
});

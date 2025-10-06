# Criteria

[![Test](https://github.com/ComplexHeart/php-criteria/actions/workflows/test.yml/badge.svg)](https://github.com/ComplexHeart/php-criteria/actions/workflows/test.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=ComplexHeart_php-criteria&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=ComplexHeart_php-criteria)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=ComplexHeart_php-criteria&metric=coverage)](https://sonarcloud.io/summary/new_code?id=ComplexHeart_php-criteria)

Small implementation of a criteria pattern in PHP for Complex Heart SDK. Compose several filters using fluent
interface.

## Installation

Just install the package from Packagist using composer:

```bash
composer require complex-heart/criteria
```

## Usage

Import the class and use the fluent interface:

```php
namespace ComplexHeart\Domain\Criteria;

// Match the users with status active and more than 7k followers and from Spain and France
$g1 = FilterGroup::create()        
    ->addFilterEqual('status', 1)
    ->addFilterGreaterThan('followers', 7000)
    ->addFilterIn('country', ['es', 'fr']);

$criteria = Criteria::default()
    ->withFilterGroup($g1)
    ->withOrderBy('surname')
    ->withOrderType('asc')
    ->withPageLimit(25)
    ->withPageOffset(50);

$users = $repository->match($criteria);

// alternative, same as above
$criteria = Criteria::default()
    ->withFilterGroup(FilterGroup::create()
        ->addFilterEqual('status', 1)
        ->addFilterGreaterThan('followers', 7000)
        ->addFilterIn('country', ['es', 'fr']))
    ->withOrderBy('surname')
    ->withOrderType('asc')
    ->withPageLimit(25)
    ->withPageOffset(50);

// In SQL, we may have something like:
// WHERE status = 1 AND followers >= 700 AND country in ('es', 'fr')

$users = $repository->match($criteria);
```

A `FilterGroup` is a set of filters or conditions that must match all together (`AND`). To match one group or another
(`OR`), just add more `FilterGroup`.

```php
// Match articles with given term in title, or in tagline, or in content.
$criteria = Criteria::default()
    ->withFilterGroup(FilterGroup::create()->addFilterContains('title', $term))
    ->withFilterGroup(FilterGroup::create()->addFilterContains('tagline', $term))
    ->withFilterGroup(FilterGroup::create()->addFilterContains('content', $term))
    ->withOrderBy('created_at')
    ->withOrderType(Order::TYPE_ASC)
    ->withPageNumber(3);
```

# Criteria (a.k.a Filter)

[![Test](https://github.com/ComplexHeart/php-criteria/actions/workflows/test.yml/badge.svg)](https://github.com/ComplexHeart/php-criteria/actions/workflows/test.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=ComplexHeart_php-criteria&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=ComplexHeart_php-criteria)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=ComplexHeart_php-criteria&metric=coverage)](https://sonarcloud.io/summary/new_code?id=ComplexHeart_php-criteria)

Small implementation of a filter criteria pattern in PHP for Complex Heart SDK. Compose several filters using fluent
interface.

## Installation

Just install the package from Packagist using composer:

```bash
composer require complexheart/php-criteria
```

## Usage

Just import the class:

```php
namespace ComplexHeart\Test\Domain\Criteria;

$criteria = Criteria::createDefault()
    ->addFilterEqual('name', 'Vincent')
    ->addFilterNotEqual('surname', 'winnfield')
    ->addFilterGreaterThan('money', '10000')
    ->addFilterGreaterOrEqualThan('age', '35')
    ->addFilterLessThan('cars', '2')
    ->addFilterLessOrEqualThan('houses', '2')
    ->addFilterLike('favoriteMeal', 'pork')
    ->addFilterIn('boss', ['marcellus', 'mia'])
    ->addFilterNotIn('hates', ['ringo', 'yolanda'])
    ->withOrder(Order::createDescBy('name'))
    ->withOrderBy('surname')
    ->withOrderType(Order::TYPE_ASC)
    ->withPageLimit(10)
    ->withPageOffset(5)

$customers = $customerRepository->match($criteria);
```

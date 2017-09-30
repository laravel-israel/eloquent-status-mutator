# Eloquent Status Mutator
[![Build Status](https://travis-ci.org/laravel-israel/eloquent-status-mutator.svg?branch=master)](https://travis-ci.org/laravel-israel/eloquent-status-mutator)
[![Latest Stable Version](https://poser.pugx.org/laravel-israel/eloquent-status-mutator/v/stable)](https://packagist.org/packages/laravel-israel/eloquent-status-mutator)
[![Total Downloads](https://poser.pugx.org/laravel-israel/eloquent-status-mutator/downloads)](https://packagist.org/packages/laravel-israel/eloquent-status-mutator)
[![License](https://poser.pugx.org/laravel-israel/eloquent-status-mutator/license)](https://packagist.org/packages/laravel-israel/eloquent-status-mutator)

Handling status changes of a model is always a pain.

Eloquent Status Mutator provides is a simple trait which enforces correct status changes & some more cool features. 

## Usage
### Define Statuses
Define the statuses of the model in the `statuses` property:

```php
class Order extends Model
{
    use HasStatus;
    
    protected $statuses = [
        'opened'    => [],
        'paid'      => ['from' => 'opened'],
        'shipped'   => ['from' => 'paid'],
        'arrived'   => ['from' => 'shipped'],
        'cancelled' => ['from' => ['opened', 'paid', 'shipped']],
    ];
}
```

### Automatic Status Enforcement
The package makes sure that only listed statuses can be set:

```php
$order->status = 'opened'; // OK

$order->status = 'invalid status'; // Throws Exception
```

### Status Flow Validation
The package enforces that status can be set only after defined statuses in its `'from'` key

```php
$order->status = 'opened';

$order->status = 'paid'; // OK

$order->status = 'arrived'; // Throws Exception
```

### Helpers

```php
$order->status = 'paid';

if ($order->is('paid')) {
    echo 'The order is shipped';
}

if ($order->canBe('shipped')) {
    echo 'The order can be shipped';
}
```

### Before and After callbacks
In some cases, we need to do something after a specific status is set - for example, send a mail after an order is cancelled.
Our package invokes a method after status change by the convention of `on` + `status name (camel cased)` 

```php
class Order extends Model
{
    use HasStatus;
    
    protected $statuses = [
        'opened'    => [],
        'paid'      => ['from' => 'opened'],
        'shipped'   => ['from' => 'paid'],
        'arrived'   => ['from' => 'shipped'],
        'cancelled' => ['from' => ['opened', 'paid', 'shipped']],
    ];
    
    public function onCancelled()
    {
        // Send cancellation mail to the user
    }
}
```

## Installation
* Request the package via composer

```
composer require laravel-israel/eloquent-status-mutator
```

* Use `HasStatus` trait in your model

```php
class Order extends Model
{
    use HasStatus;
}
```

* Define the available statuses in the model

```php
protected $statuses = [
    'opened'  => [],
    'paid'    => ['from' => 'opened'],
    'shipped' => ['from' => 'paid'],
    'arrived' => ['from' => 'shipped'],
];
```

# Eloquent Status Manager
Handling status of Eloquent models was never easier.
Just define the status flow, and the package will take care of the rest.

## Usage
Define statuses
```php
class MyModel extends Model
{
    use EloquentStatusManagerTrait;
    
    /**
     * @var array
     */
    public $statuses = [
        'in progress' => [],
        'selected' => ['from' => 'in progress'],
        'rejected' => ['from' => 'in progress'],
    ];
}
```

Model accessors
```php
if ($myModel->canBe('rejected')) {
    echo 'Model can be changed to "rejected"';
}
```

Changing status
```php
class MyModel extends Model
{
    use EloquentStatusManagerTrait;
    
    /**
     * @var array
     */
    public $statuses = [
        'in progress' => [],
        'selected' => ['from' => 'in progress'],
        'rejected' => ['from' => 'in progress'],
    ];
    
    /**
     * Reject the negotiation
     */
    public function beforeReject()
    {
        // Send mail
    }
}
```

```php
$myModel->status = 'rejected'; // Will send mail
```

## Installation
Require the package via composer

```
composer require laravel-israel/eloquent-status-manager
```

## Setup
1. Include `EloquentStatusManagerTrait` trait on your model
2. Create `$statuses` field on your model - with all the status definitions.
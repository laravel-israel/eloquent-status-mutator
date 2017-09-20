<?php

namespace LaravelIsrael\EloquentStatusManager\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use LaravelIsrael\EloquentStatusManager\EloquentStatusManagerTrait;

class ModelMock extends Model {
    use EloquentStatusManagerTrait;

    /**
     * @var array
     */
    protected $statuses = [];

    /**
     * ModelMock constructor.
     * @param array $statues
     */
    public function __construct(array $statues)
    {
        $this->statuses = $statues;
        $this->status = array_keys($statues)[0];

        parent::__construct();
    }
}
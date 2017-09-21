<?php

namespace LaravelIsrael\EloquentStatusManager\Tests\Mocks;

use Illuminate\Database\Eloquent\Model;
use LaravelIsrael\EloquentStatusManager\EloquentStatusManagerTrait;

class SampleModel extends Model
{
    use EloquentStatusManagerTrait;

    /**
     * @var array
     */
    protected $statuses = [];

    /**
     * ModelMock SampleModel.
     *
     * @param array $statues
     */
    public function __construct(array $statues)
    {
        $this->statuses = $statues;
        $this->status = array_keys($statues)[0];

        parent::__construct();
    }
}
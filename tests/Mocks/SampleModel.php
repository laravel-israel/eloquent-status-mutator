<?php

namespace LaravelIsrael\EloquentStatusMutator\Tests\Mocks;

use Illuminate\Database\Eloquent\Model;
use LaravelIsrael\EloquentStatusMutator\HasStatus;

class SampleModel extends Model
{
    use HasStatus;

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

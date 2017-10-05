<?php

namespace LaravelIsrael\EloquentStatusMutator\Exception;

use RuntimeException;
use Illuminate\Database\Eloquent\Model;

class UndefinedStatusWasSet extends RuntimeException
{
    /**
     * UndefinedStatusWasSet constructor.
     *
     * @param Model $model
     * @param string $newStatus
     */
    public function __construct(Model $model, string $newStatus)
    {
        $modelName = get_class($model);

        parent::__construct("Undefined status {$newStatus} was set for {$modelName}");
    }
}
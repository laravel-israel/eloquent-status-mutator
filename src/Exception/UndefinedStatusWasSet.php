<?php

namespace LaravelIsrael\EloquentStatusMutator\Exception;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class UndefinedStatusWasSet extends RuntimeException
{
    /**
     * UndefinedStatusWasSet constructor.
     *
     * @param Model  $model
     * @param string $newStatus
     */
    public function __construct(Model $model, string $newStatus)
    {
        $modelName = class_basename($model);

        parent::__construct("Undefined status {$newStatus} was set for {$modelName}");
    }
}

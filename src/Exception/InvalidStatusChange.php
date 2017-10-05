<?php

namespace LaravelIsrael\EloquentStatusMutator\Exception;

use RuntimeException;
use Illuminate\Database\Eloquent\Model;

class InvalidStatusChange extends RuntimeException
{
    /**
     * UndefinedStatusWasSet constructor.
     *
     * @param Model $model
     * @param string $oldStatus
     * @param string $newStatus
     */
    public function __construct(Model $model, string $oldStatus, string $newStatus)
    {
        $modelName = get_class($model);

        /** @var string $model->status */
        parent::__construct("Status of {$oldStatus} cannot be changed to {$newStatus} in {$modelName}");
    }
}
<?php

namespace LaravelIsrael\EloquentStatusMutator;

use Illuminate\Database\Eloquent\Model;
use LaravelIsrael\EloquentStatusMutator\Exception\InvalidStatusChange;
use LaravelIsrael\EloquentStatusMutator\Exception\UndefinedStatusWasSet;

/**
 * Trait HasStatus.
 *
 * @property array statuses
 * @property array attributes
 * @property string status
 *
 * @method void save
 */
trait HasStatus
{
    /**
     * @param string $newStatus
     */
    public function setStatusAttribute(string $newStatus)
    {
        if ($this->status === $newStatus) {
            return;
        }

        if (!$this->canBe($newStatus)) {
            /* @var Model $this */
            throw new InvalidStatusChange($this, $this->status, $newStatus);
        }

        $this->runOnChangeCallback($newStatus);

        $this->attributes['status'] = $newStatus;
    }

    /**
     * @param $status
     *
     * @return bool
     */
    public function canBe($status): bool
    {
        $this->throwExceptionIfStatusInvalid($status);

        return $this->checkFrom($status) && $this->checkNotFrom($status);
    }

    /**
     * @param $status
     */
    private function throwExceptionIfStatusInvalid(string $status)
    {
        if (!array_key_exists($status, $this->statuses)) {
            /* @var Model $this */
            throw new UndefinedStatusWasSet($this, $status);
        }
    }

    /**
     * @param string $status
     */
    private function runOnChangeCallback(string $status)
    {
        $formattedStatus = studly_case($status);
        $method = "on{$formattedStatus}";

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * @param $status
     *
     * @return bool
     */
    private function checkFrom($status): bool
    {
        if (!array_key_exists('from', $this->statuses[$status])) {
            return true;
        }

        $from = $this->statuses[$status]['from'];

        if (is_string($from)) {
            return $this->status === $from;
        }

        foreach ($from as $toOption) {
            if ($this->status === $toOption) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $status
     *
     * @return bool
     */
    private function checkNotFrom($status): bool
    {
        if (!array_key_exists('not-from', $this->statuses[$status])) {
            return true;
        }

        $from = $this->statuses[$status]['not-from'];

        if (is_string($from)) {
            return $this->status !== $from;
        }

        foreach ($from as $toOption) {
            if ($this->status === $toOption) {
                return false;
            }
        }

        return true;
    }
}

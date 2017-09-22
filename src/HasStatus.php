<?php

namespace LaravelIsrael\EloquentStatusMutator;

use RuntimeException;

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
            throw new RuntimeException("Status of {$this->status} cannot be changed to {$newStatus}");
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

        // Return true if the from key was not defined
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
     */
    private function throwExceptionIfStatusInvalid(string $status)
    {
        if (!array_key_exists($status, $this->statuses)) {
            throw new RuntimeException("{$status} is not a valid status");
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
}
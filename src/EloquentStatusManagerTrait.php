<?php

namespace LaravelIsrael\EloquentStatusManager;

use RuntimeException;

/**
 * Trait EloquentStatusManagerTrait
 * @package LaravelIsrael
 *
 * @property array statuses
 * @property array attributes
 * @property string status
 *
 * @method void save
 */
trait EloquentStatusManagerTrait
{
    /**
     * @param string $newStatus
     */
    public function setStatusAttribute(string $newStatus)
    {
        if ($this->status === $newStatus) {
            return;
        }

        if ( ! $this->canBe($newStatus)) {
            throw new RuntimeException("Status of {$this->status} cannot be changed to {$newStatus}");
        }

        if ( ! is_null($this->status)) {
            $this->runAfterCallback($this->status);
        }

        $this->runBeforeCallback($newStatus);

        $this->attributes['status'] = $newStatus;
    }

    /**
     * @param $status
     * @return bool
     */
    public function canBe($status): bool
    {
        $this->throwExceptionIfStatusInvalid($status);

        // Return true if the from key was not defined
        if ( ! array_key_exists('from', $this->statuses[$status])) {
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
        if ( ! array_key_exists($status, $this->statuses)) {
            throw new RuntimeException("{$status} is not a valid status");
        }
    }

    /**
     * @param string $status
     */
    private function runAfterCallback(string $status)
    {
        $method = "after{$this->slug_status($status)}";

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * @param string $status
     */
    private function runBeforeCallback(string $status)
    {
        $method = "before{$this->slug_status($status)}";

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * @param $status
     * @return string
     */
    public function slug_status($status): string
    {
        $capitalizedStatus = ucfirst(camel_case($status));
        return $capitalizedStatus;
    }
}
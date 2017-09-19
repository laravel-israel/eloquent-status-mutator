<?php

namespace LaravelIsrael;

use RuntimeException;

/**
 * Trait EloquentStatusManagerTrait
 * @package LaravelIsrael
 *
 * @property array statuses
 * @property string status
 *
 * @method void save
 */
trait EloquentStatusManagerTrait
{
    /**
     * @param $status
     * @return bool
     */
    public function is($status): bool
    {
        $this->throwExceptionIfStatusInvalid($status);

        return $this->status === $status;
    }

    /**
     * @param $status
     * @return bool
     */
    public function canBe($status): bool
    {
        $this->throwExceptionIfStatusInvalid($status);

        $currentStatus = $this->status;

        // Return true if the from key was not defined
        if ( ! array_key_exists('to', $this->statuses[$currentStatus])) {
            return true;
        }

        $to = $this->statuses[$currentStatus]['to'];

        if (is_string($to)) {
            return $status === $this->statuses[$currentStatus]['to'];
        }

        foreach ($to as $toOption) {
            if ($status === $toOption) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $status
     */
    public function setAs($status)
    {
        $this->throwExceptionIfStatusInvalid($status);

        if ( ! $this->canBe($status)) {
            $this->throwError("Status of {$this->status} cannot be changed to {$status}");
        }

        $onStatusChangedMethod = $this->getOnStatusChangedMethod($this->status);
        if (method_exists($this, $onStatusChangedMethod)) {
            $this->$onStatusChangedMethod();
        }

        $onStatusIsChangingMethod = $this->getOnStatusIsChangingMethod($status);
        if (method_exists($this, $onStatusIsChangingMethod)) {
            $this->$onStatusIsChangingMethod();
        }

        $this->status = $status;
        $this->save();
    }

    /**
     * @param $status
     */
    private function throwExceptionIfStatusInvalid($status)
    {
        if ( ! array_key_exists($status, $this->statuses)) {
            $this->throwError("{$status} is not a valid status");
        }
    }

    /**
     * @param string $error
     */
    private function throwError(string $error)
    {
        throw new RuntimeException($error);
    }

    /**
     * @param $status
     * @return string
     */
    public function getOnStatusChangedMethod($status): string
    {
        $capitalizedStatus = ucfirst(camel_case($status));

        return "after{$capitalizedStatus}";
    }

    /**
     * @param $status
     * @return string
     */
    public function getOnStatusIsChangingMethod($status): string
    {
        $capitalizedStatus = ucfirst(camel_case($status));

        return "before{$capitalizedStatus}";
    }
}
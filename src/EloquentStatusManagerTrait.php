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
    public function setStatusAttribute(string $status)
    {
        if ( ! $this->canBe($status)) {
            throw new RuntimeException("Status of {$this->status} cannot be changed to {$status}");
        }

        $this->attributes['status'] = $status;
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

//    /**
//     * @param $status
//     * @return bool
//     */
//    public function is($status): bool
//    {
//        $this->throwExceptionIfStatusInvalid($status);
//
//        return $this->status === $status;
//    }
//
//
//    /**
//     * @param $status
//     */
//    public function setAs($status)
//    {
//        $this->throwExceptionIfStatusInvalid($status);
//
//        if ( ! $this->canBe($status)) {
//            $this->throwError("Status of {$this->status} cannot be changed to {$status}");
//        }
//
//        $onStatusChangedMethod = $this->getOnStatusChangedMethod($this->status);
//        if (method_exists($this, $onStatusChangedMethod)) {
//            $this->$onStatusChangedMethod();
//        }
//
//        $onStatusIsChangingMethod = $this->getOnStatusIsChangingMethod($status);
//        if (method_exists($this, $onStatusIsChangingMethod)) {
//            $this->$onStatusIsChangingMethod();
//        }
//
//        $this->status = $status;
//        $this->save();
//    }
//
//
//    /**
//     * @param $status
//     * @return string
//     */
//    public function getOnStatusChangedMethod($status): string
//    {
//        $capitalizedStatus = ucfirst(camel_case($status));
//
//        return "after{$capitalizedStatus}";
//    }
//
//    /**
//     * @param $status
//     * @return string
//     */
//    public function getOnStatusIsChangingMethod($status): string
//    {
//        $capitalizedStatus = ucfirst(camel_case($status));
//
//        return "before{$capitalizedStatus}";
//    }
}
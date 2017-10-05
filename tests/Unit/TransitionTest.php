<?php

namespace LaravelIsrael\EloquentStatusMutator\Tests\Unit;

use LaravelIsrael\EloquentStatusMutator\Exception\InvalidStatusChange;
use LaravelIsrael\EloquentStatusMutator\Exception\UndefinedStatusWasSet;
use LaravelIsrael\EloquentStatusMutator\Tests\Mocks\SampleModel;
use LaravelIsrael\EloquentStatusMutator\Tests\TestCase;

class TransitionTest extends TestCase
{
    public function test_valid_status_can_be_set()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => [],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_invalid_status_throws_exception()
    {
        $model = new SampleModel([
            'aaa'  => [],
        ]);

        $this->expectException(UndefinedStatusWasSet::class);
        $this->expectExceptionMessage("Undefined status bbb was set for SampleModel");

        $model->status = 'bbb';
    }

    public function test_valid_single_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => 'aaa'],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_invalid_single_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => 'ccc'],
        ]);

        $this->expectException(InvalidStatusChange::class);
        $this->expectExceptionMessage("Status of aaa cannot be changed to bbb in SampleModel");

        $model->status = 'bbb';
    }

    public function test_valid_multiple_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => ['aaa']],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_invalid_multiple_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => ['ccc']],
        ]);

        $this->expectException(InvalidStatusChange::class);
        $this->expectExceptionMessage("Status of aaa cannot be changed to bbb in SampleModel");

        $model->status = 'bbb';
    }

    public function test_valid_single_not_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['not-from' => 'ccc'],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_invalid_single_not_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['not-from' => 'aaa'],
        ]);

        $this->expectException(InvalidStatusChange::class);
        $this->expectExceptionMessage("Status of aaa cannot be changed to bbb in SampleModel");

        $model->status = 'bbb';
    }

    public function test_valid_multiple_not_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['not-from' => ['ccc']],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_invalid_multiple_not_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['not-from' => ['aaa']],
        ]);

        $this->expectException(InvalidStatusChange::class);
        $this->expectExceptionMessage("Status of aaa cannot be changed to bbb in SampleModel");

        $model->status = 'bbb';
    }
}

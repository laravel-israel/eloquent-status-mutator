<?php

namespace LaravelIsrael\EloquentStatusMutator\Tests\Unit;

use LaravelIsrael\EloquentStatusMutator\Exception\InvalidStatusChange;
use LaravelIsrael\EloquentStatusMutator\Tests\Mocks\SampleModel;
use LaravelIsrael\EloquentStatusMutator\Tests\TestCase;
use LaravelIsrael\EloquentStatusMutator\Exception\UndefinedStatusWasSet;

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

        $modelName = get_class($model);

        $this->expectException(UndefinedStatusWasSet::class);
        $this->expectExceptionMessage("Undefined status bbb was set for {$modelName}");

        $model->status = 'bbb';
    }

    public function test_correct_transition_with_single_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => 'aaa'],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_incorrect_transition_with_single_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => 'ccc'],
        ]);

        $modelName = get_class($model);

        $this->expectException(InvalidStatusChange::class);
        $this->expectExceptionMessage("Status of aaa cannot be changed to bbb in {$modelName}");

        $model->status = 'bbb';
    }

    public function test_correct_transition_with_multiple_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => ['aaa']],
        ]);

        $model->status = 'bbb';

        $this->addToAssertionCount(1);
    }

    public function test_incorrect_transition_with_multiple_from()
    {
        $model = new SampleModel([
            'aaa'  => [],
            'bbb'  => ['from' => ['ccc']],
        ]);

        $modelName = get_class($model);

        $this->expectException(InvalidStatusChange::class);
        $this->expectExceptionMessage("Status of aaa cannot be changed to bbb in {$modelName}");

        $model->status = 'bbb';
    }
}

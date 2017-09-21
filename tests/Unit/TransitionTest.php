<?php

namespace LaravelIsrael\EloquentStatusManager\Tests\Unit;

use LaravelIsrael\EloquentStatusManager\Tests\Mocks\SampleModel;
use LaravelIsrael\EloquentStatusManager\Tests\TestCase;
use RuntimeException;

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

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('bbb is not a valid status');

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
        
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Status of aaa cannot be changed to bbb');

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

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Status of aaa cannot be changed to bbb');

        $model->status = 'bbb';
    }
}

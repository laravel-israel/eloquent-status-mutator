<?php

namespace LaravelIsrael\EloquentStatusMutator\Tests\Unit;

use LaravelIsrael\EloquentStatusMutator\Tests\Mocks\SampleModel;
use LaravelIsrael\EloquentStatusMutator\Tests\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class CallbackTest extends TestCase
{
    /**
     * @var SampleModel|PHPUnit_Framework_MockObject_MockObject
     */
    private $model;

    public function setUp()
    {
        $statuses = [
            'aaa' => [],
            'bbb' => [],
        ];

        $this->model = $this->getMockBuilder(SampleModel::class)
            ->setConstructorArgs([$statuses])
            ->setMethods(['onBbb'])
            ->getMock();
    }

    public function test_callback()
    {
        $this->model
            ->expects($this->once())
            ->method('onBbb');

        $this->model->status = 'bbb';
    }

    public function test_callback_is_not_executed_when_the_same_status_is_set()
    {
        $this->model
            ->expects($this->once())
            ->method('onBbb');

        $this->model->status = 'bbb';
        $this->model->status = 'bbb';
        $this->model->status = 'bbb';
        $this->model->status = 'bbb';
    }
}

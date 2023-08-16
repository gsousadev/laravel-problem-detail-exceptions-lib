<?php

namespace Tests\Unit;

use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;
use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ServerException;
use Orchestra\Testbench\TestCase;

class ProblemDetailExceptionTest extends TestCase
{
    public function testShouldReturnFieldsByConfig()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', false);
        config()->set('problem-detail-exceptions.available_fields_list', [
            ExceptionsFieldsEnum::MESSAGE,
            ExceptionsFieldsEnum::STATUS
        ]);
        config()->set('problem-detail-exceptions.renderable_fields_list', [
            ExceptionsFieldsEnum::STATUS
        ]);

        $exception = new ServerException();

        $this->assertEquals(
            [
                'message' => 'Erro interno do servidor - O servidor encontrou um erro interno e não foi capaz de completar sua requisição',
                'status'  => '500'
            ],
            $exception->toArray()
        );
    }
}

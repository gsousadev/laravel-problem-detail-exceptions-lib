<?php

namespace Tests\Unit\Exceptions;

use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;
use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ClientException;
use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ServerException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;

class ProblemDetailExceptionTest extends TestCase
{
    public function testShouldReturnFieldsByConfig()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', true);
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
                'message' => 'Erro interno do servidor - O servidor encontrou um erro ' .
                    'interno e não foi capaz de completar sua requisição',
                'status'  => '500'
            ],
            $exception->toArray()
        );

        $this->assertEquals(500, $exception->getCode());
        $this->assertInstanceOf(Response::class, $exception->render());

        Log::shouldReceive('sharedContext');
        Log::shouldReceive('error')->with(
            '[TEST][UNEX0001]',
            [
                'message' => 'Erro interno do servidor - O servidor encontrou um erro ' .
                    'interno e não foi capaz de completar sua requisição',
                'status'  => '500'
            ]
        );

        $this->assertTrue($exception->report());


        $this->assertEquals('Erro interno do servidor', $exception->getTitle());
        $this->assertEquals(
            'O servidor encontrou um erro interno e não foi capaz de completar sua requisição',
            $exception->getDetail()
        );
        $this->assertEquals('Erro interno do servidor', $exception->getUserTitle());
        $this->assertEquals(
            'O servidor encontrou um erro interno e não foi capaz de completar sua requisição.',
            $exception->getUserMessage()
        );
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getHttpStatus());
        $this->assertEquals('UNEX0001', $exception->getInternalCode());
        $this->assertEquals(null, $exception->getPrevious());
    }

    public function testShouldThrowExceptionWhenFieldsConfigIsNotArray()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', false);
        config()->set('problem-detail-exceptions.available_fields_list', 'teste');
        config()->set('problem-detail-exceptions.renderable_fields_list', 'teste');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('renderable_fields must be an array in config/problem-detail-exceptions.php');

        new ClientException();
    }

    public function testShouldThrowExceptionWhenFieldsConfigCountIsZero()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', false);
        config()->set('problem-detail-exceptions.available_fields_list', []);
        config()->set('problem-detail-exceptions.renderable_fields_list', []);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'renderable_fields must have at least one element in ' .
            'config/problem-detail-exceptions.php'
        );

        new ClientException();
    }

    public function testShouldThrowExceptionWhenSameFieldConfigTypeIsNotSupported()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', false);
        config()->set(
            'problem-detail-exceptions.available_fields_list',
            [
                'teste',
                ExceptionsFieldsEnum::MESSAGE
            ]
        );
        config()->set(
            'problem-detail-exceptions.renderable_fields_list',
            [
                ExceptionsFieldsEnum::MESSAGE,
                ExceptionsFieldsEnum::STATUS
            ]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'fields must be an array of ExceptionsFieldsEnum in ' .
            'config/problem-detail-exceptions.php'
        );

        new ClientException();
    }

    public function testShouldThrowExceptionWhenSameRenderableFieldConfigTypeIsNotSupported()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', false);
        config()->set('problem-detail-exceptions.available_fields_list', [
            ExceptionsFieldsEnum::STATUS,
            ExceptionsFieldsEnum::MESSAGE
        ]);
        config()->set(
            'problem-detail-exceptions.renderable_fields_list',
            [
                'teste',
                ExceptionsFieldsEnum::STATUS
            ]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'renderable_fields must be an array of ExceptionsFieldsEnum in ' .
            'config/problem-detail-exceptions.php'
        );

        new ClientException();
    }

}

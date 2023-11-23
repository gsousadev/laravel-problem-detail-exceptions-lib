<?php

namespace Tests\Unit;

use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;
use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ServerException;
use Gsousadev\LaravelProblemDetailExceptions\Exceptions\UnexpectedException;
use Orchestra\Testbench\TestCase;

class ConfigTest extends TestCase
{
    public function testShouldReturnFieldsByConfig()
    {
        config()->set('problem-detail-exceptions.app_name', 'TEST');
        config()->set('problem-detail-exceptions.enable_log_in_exception', false);
        config()->set('problem-detail-exceptions.available_fields_list', [
            ExceptionsFieldsEnum::TITLE,
            ExceptionsFieldsEnum::STATUS
        ]);
        config()->set('problem-detail-exceptions.renderable_fields_list', [
            ExceptionsFieldsEnum::STATUS
        ]);

        $exception = new UnexpectedException(new \Exception('Teste Erro Sem Mapeamento', 0));

        $this->assertEquals(
            [
                'title' => 'Erro Inesperado',
                'status'  => '500'
            ],
            $exception->toArray()
        );
    }
}

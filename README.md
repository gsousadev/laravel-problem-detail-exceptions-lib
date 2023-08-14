# Laravel Problem Detail Exceptions

[![Versão](https://img.shields.io/badge/vers%C3%A3o-0.1.0-beta)](https://github.com/seu-usuario/sua-lib/releases)
[![Licença](https://img.shields.io/badge/licen%C3%A7a-MIT-green)](https://opensource.org/licenses/MIT)

Esse projeto tem por objetivo prover uma biblioteca Laravel que permite implementar de maneira simples e rápida um padrão de exceptions, seguindo os conceitos da [RFC de problem details](https://datatracker.ietf.org/doc/html/rfc7807)

## Instalação

O pacote pode ser instalado usando [Composer](https://getcomposer.org/):

```bash
composer require gsousadev/laravel-problem-detail-exceptions-lib
```

Após a instalação é necessário publicar os arquivos de configuração do pacote. Use o comando abaixo para isso:

```bash
php artisan vendor:publish --tag=problem-detail-exceptions
```

Após a publicação do pacote devem ser criados dois arquivos no projeto, o arquivo de configuração `/config/problem-detail-exceptions.php` e uma classe de excessão de exemplo neste caminho `App\Exceptions\ExampleException`.

## Configurando

Este pacote permite algumas configurações de customização. Para isso deve-se user o arquivo ```problem-detail-exceptions.php```

Para ter dados coerentes dentro do fluxo é importante ter duas variáveis de ambiente implementadas:
- **PROBLEM_DETAIL_EXCEPTION_APP_NAME** : Indica o nome que pode aparecer nos logs referente ao nome do projeto ou app que o pacote esta sendo usado.
- **PROBLEM_DETAIL_EXCEPTION_GENERATE_LOGS** : Esta variável permite que sejam ligados e desligados os logs que devem ser publicados em casos de erro.

Existe também uma configuração que pode ser feita dentro do arquivo de configuração, informando quais campos devem ser considerados para exceptions de APIs

Existir duas configurações possíveis: Campos que devem ser usados em qualquer contexto chamado `fields` e campos que devem ser mostrados em chamadas HTTP como resposta em casos de erro `renderable_fields`.

***Obs: NÃO RECOMENDAMOS o uso de todos os campos disponíveis, nas respostas HTTP, pois alguns campos podem ter informaçÕes que devem ser guardadas em segurança de não devem ser disponíveis para qualquer pessoa.***

Todos os campos devem ser cadastrados de acordo com uma classe de Enum, que esta localizada tambem no projeto.

Os campos disponíveis estão dentro do Enum a seguir:

```php
<?php

namespace Gsousadev\LaravelProblemDetailExceptions\Enums;

enum ExceptionsFieldsEnum: string
{
    case TYPE = 'type';
    case TITLE = 'title';
    case STATUS = 'status';
    case DETAIL = 'detail';
    case INTERNAL_CODE = 'internal_code';
    case MESSAGE = 'message';
    case USER_MESSAGE = 'user_message';
    case USER_TITLE = 'user_title';
    case LOCATION = 'location';
    case TRACE_ID = 'trace_id';
}

```


## Usando

Para usar o pacote recomendamos que sejam criadas excessões extendendo da classe `Gsousadev\LaravelProblemDetailExceptions\Exceptions\ProblemDetailException` conforme a classe de exemplo abaixo:

```php
...

use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ProblemDetailException;

class ExampleException extends ProblemDetailException
```

Em seguida devemos criar dentro do construtor uma chamada para o construtor da classe Pai passando os parâmetros necessários para a construção da exception.

```php
...

    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            title:        'Titulo curto para erro. Deve ser imutável',
            detail:       'Descrição mais detalhada do erro podendo conter variaveis dinâmicas.' .
                          'Pode ser mutável a cada lançamento dependendo do contexto',
            userTitle:    'Titulo amigavel para usuário final que pode ver o erro',
            userMessage:  'Detalhamento amigavel para usuário que pode ver o erro',
            httpStatus:  500,
            internalCode: 'SHRD0001',
            previous:     $previous
        );
    }
...

```

O resultado final deve ser algo como o exemplo abaixo

```php
<?php

declare(strict_types=1);

namespace App\Exceptions;

use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ProblemDetailException;

class ExampleException extends ProblemDetailException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            title:        'Titulo curto para erro. Deve ser imutável',
            detail:       'Descrição mais detalhada do erro podendo conter variaveis dinâmicas.' .
                          'Pode ser mutável a cada lançamento dependendo do contexto',
            userTitle:    'Titulo amigavel para usuário final que pode ver o erro',
            userMessage:  'Detalhamento amigavel para usuário que pode ver o erro',
            httpStatus:  500,
            internalCode: 'SHRD0001',
            previous:     $previous
        );
    }

}

```

Desta maneira é possível ter uma exception bem legível, completa e com uma forma de invocação simples e direta


```php
try {
...
} catch(\Exception $exception){
    throw new ExampleException($exception);
}
```

## Contribuindo

O projeto esta em fase de construção da ideia inicial mas apontamentos de melhorias é muito importante para o crescimento. Para informar uma melhoria use as Issues do github.

## Licença

[MIT](https://choosealicense.com/licenses/mit/)

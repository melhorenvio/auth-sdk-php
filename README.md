# Auth SDK - Serviço de autorização do Melhor Envio.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/shipment-sdk-php.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk-php)
[![Build Status](https://img.shields.io/travis/melhorenvio/shipment-sdk-php/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/shipment-sdk-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/shipment-sdk-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/shipment-sdk-php)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/shipment-sdk-php.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk-php)

Agora você pode incluir o processo de autorização do Melhor Envio no seu projeto de e-commerce de forma rápida e simples.

## Índice

* [Instalação](#instalacao)
* [Cofiguração Inicial](#configuração-inicial)
* [Exemplos de uso](#exemplos-de-uso)
    * [Gerando o Access Token](#gerando-o-access-token)
    * [Refresh Token](#refresh-token)
* [Testes](##Testes)
* [Changelog](##Changelog)
* [Contribuindo](##Contribuindo)
* [Segurança](##Segurança)
* [Créditos](##Créditos)
* [Licença](##Licença)

## Dependências

### require
* PHP >= 7.1
* Ext-json = *
* Guzzlehttp/guzzle >= 6.5

### require-dev
* phpunit/phpunit >= 5


## Instalação

Você pode instalar o pacote via composer:

```bash
composer require melhorenvio/sdk-auth
```

## Configuração inicial

Logo após a instalação concluída, você irá preparar os dados a serem passados como parâmetro para a classe OAuth,  lembrando que esses dados são os mesmos que são gerados por você no site do Melhor Envio na criação do aplicativo.

Se você ainda não fez esse passo, basta acessar https://melhorenvio.com.br/painel/gerenciar/tokens.

```php
$appData = [
    'client_id' => 'your-client-id',
    'client_secret' => 'your-client-secret',
    'redirect_uri' => 'your-redirect-uri'
];
```

Em seguida, você instanciará a classe OAuth passando os parâmetros que você recebe acima.

``` php
$provider = new OAuth2($appData['client_id'], $appData['client_secret'], $appData['redirect_uri']);
```


## Exemplos de uso
 
Logo em seguida utilizado a variável onde foi instanciada a classe OAuth, $provider, você irá selecionar os
escopos presentes na sua aplicação, podendo ser um ou vários.

Lembrando que os escpopos estão disponíveis para consulta na documentação da API do Melhor Envio, neste link: https://docs.menv.io/?version=latest#03becc90-8b38-47bd-ba14-7994017462f0

Logo em seguida, faça o redirecionamento para URL retornada pelo método getAuthorizationUrl().


``` php
$provider->setScopes('shipping-calculate');
header("Location: {$provider->getAuthorizationUrl()}");
exit;
```


## Gerando o Access Token

Bom até aqui vimos como instanciar a classe OAuth, escolher os escopos a serem utilizados e montar a URL, porém quais
são os próximos passos? 

Temos o seguinte cenário, onde você irá utilizar o método getAccessToken(), para obter a resposta com as informações das credenciais necessárias para você ter êxito na realização de requisições para a API do Melhor Envio.

```php
$authData[] = $provider->getAccessToken($_GET['code'], $_GET['state']);
```

Um exemplo da resposta dessas informações:

```json
Array
(
    [0] => Array
        (
            [token_type] => Bearer
            [expires_in] => timestampResponse
            [access_token] => yourToken
            [refresh_token] => yourRefreshToken
        )

)
```


## Refresh Token

Após 30 dias o seu token irá expirar. Mas não se preocupe, o pacote oferece o método de refresh token para que você 
deixe sua aplicação preparada para quando isso acontecer.

Como essas informações foram geradas anteriormente, você irá utilizar o método refreshToken passando como parâmetro o dado respectivo, tendo como resposta um novo token.

```php
$newAuthData = $provider->refreshToken($authData['refresh_token']);
```


### Testing

``` bash
composer test
```

### Changelog

Consulte [CHANGELOG](CHANGELOG.md) para mais informações de alterações recentes.

## Contribuindo

Consulte [CONTRIBUTING](CONTRIBUTING.md) para mais detalhes.

### Segurança

Se você descobrir algum problema de segurança, por favor, envie um e-mail para tecnologia@melhorenvio.com, ao invés de usar um *issue tracker*.

## Créditos

- [Rodrigo Silveira](https://github.com/rodriigogs)
- [Marçal Pizzi](https://github.com/marcalpizzi)
- [Pedro Barros](https://github.com/pedrobarros05)

## Licença

Melhor Envio. Consulte [Arquivo de lincença](LICENSE.md) para mais informações.

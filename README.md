# Auth SDK - Serviço de autorização do Melhor Envio.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/shipment-sdk-php.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk-php)
[![Build Status](https://img.shields.io/travis/melhorenvio/shipment-sdk-php/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/shipment-sdk-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/shipment-sdk-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/shipment-sdk-php)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/shipment-sdk-php.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/shipment-sdk-php)

Agora você pode incluir o processo de autorização do Melhor Envio no seu projeto de e-commerce de forma rápida e simples.

## Índice

* [Instalação](#instalacao)
* [Cofiguração Inicial](#configuração-inicial)
* [Exemplos de uso](#Criando-a-instância-calculadora)
* [Mais exemplos](##Mais-Exemplos)
* [Testes](##Testes)
* [Changelog](##Changelog)
* [Contribuindo](##Contribuindo)
* [Segurança](##Segurança)
* [Créditos](##Créditos)
* [Licença](##Licença)

## Dependências

### require
* PHP >= 5.6
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

Logo após a instalação concluída, você irá preparar os dados a serem passados como parâmetro para a classe OAuth,
lembrando que esses dados são os mesmos que são gerados por você no site do Melhor Envio na criação do aplicativo.
Se você ainda não fez esse passo, basta acessar (link para criação).

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

Lembrando que os escpopos estão disponíveis para consulta na documentação da API do Melhor Envio. (link para api)

Logo em seguida, faça o redirecionamento para URL retornada pelo método getAuthorizationUrl().


``` php
$provider->setScopes('shipping-calculate');
header("Location: {$provider->getAuthorizationUrl()}");
exit;
```


## Próximos passos

Bom até aqui vimos como instanciar a classe OAuth, escolher os escopos a serem utilizados e montar a URL, porém quais
são os próximos passos? 

Temos o seguinte cenário, onde você irá utilizar o método getAccessToken(), disponibilizado no pacote,
para obter a resposta com as informações necessárias para você ter êxito no processo de autorização.

```php
$authData[] = $provider->getAccessToken($_GET['code'], $_GET['state']);
```

Um exemplo da resposta dessas informações:

(aqui exemplo de json)


## Refresh Token

Após 30 dias o seu token irá expirar. Mas não se preocupe, o pacote oferece o método de refresh token para que você 
deixe sua aplicação preparada para quando isso acontecer.

Como essas informações foram geradas anteriormente, você irá utilizar o método refresToken, disponibilizado no pacote,
passando como parâmetro o dado referente, tendo como resposta esse novo token.

```php
$newAuthData = $provider->refreshToken($authData['refresh_token']);
```


### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email rodrigo.silveira@melhorenvio.com instead of using the issue tracker.

## Credits

- [Rodrigo Silveira](https://github.com/rodriigogs)
- [Marçal Pizzi](https://github.com/)
- [Pedro Barros](https://github.com/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).

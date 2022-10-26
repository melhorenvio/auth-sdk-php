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
* PHP >= 7.4
* Ext-json = *
* Guzzlehttp/guzzle >= 7.0

### require-dev
* phpunit/phpunit >= 9.5


## Instalação

Você pode instalar o pacote via composer:

```bash
composer require melhorenvio/auth-sdk-php
```

## Configuração inicial

Logo após a instalação concluída, você irá preparar os dados a serem passados como parâmetro para a classe **OAuth**, lembrando que esses dados são os mesmos que são gerados por você no site do Melhor Envio na criação do aplicativo.

Se você ainda não fez esse passo, basta acessar https://melhorenvio.com.br/painel/gerenciar/tokens.

```php
$appData = [
    'client_id' => 'your-client-id',
    'client_secret' => 'your-client-secret',
    'redirect_uri' => 'your-redirect-uri'
];
```

Em seguida, você instanciará a classe **OAuth** passando os parâmetros que você recebe acima.

``` php
$provider = new OAuth2($appData['client_id'], $appData['client_secret'], $appData['redirect_uri']);
```


## Exemplos de uso

Uma vez a classe OAuth estando estanciada, você irá informar os escopos que serão necessários para a sua aplicação utilizando o método ```setScopes()```, podendo ser um ou vários. Scopes são as permissões para as ações que o usuário pode ter com o token gerado, por exemplo, ver pedidos, ver carrinho de compras, enviar pedidos, etc.

Lembrando que os escopos estão disponíveis para consulta na documentação da API do Melhor Envio, neste link: https://docs.menv.io/?version=latest#03becc90-8b38-47bd-ba14-7994017462f0

Logo em seguida, faça o redirecionamento para URL retornada pelo método ```getAuthorizationUrl()```.


```php
$provider->setScopes('shipping-calculate');
header("Location: {$provider->getAuthorizationUrl()}");
exit;
```

## Visualizando Scopes utilizados

Para visualizar o scopes utilizados, ou seja, as permissões de acesso para token que será gerado, basta utilizar o método ```getScopes()```, esse método irá retornar um array com os scopes utilizados.

```php
$authData[] = $provider->getScopes();
```


## Gerando o Access Token

Bom até aqui vimos como instanciar a classe OAuth, escolher os escopos a serem utilizados e montar a URL, porém quais
são os próximos passos? 

Temos o seguinte cenário, onde você irá utilizar o método ```getAccessToken()```, para obter a resposta com as informações das credenciais necessárias para você ter êxito na realização de requisições para a API do Melhor Envio.

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
            [expires_in] => your-token-expiration-timestamp
            [access_token] => your-token
            [refresh_token] => your-refresh-token
        )

)
```

## Definindo URL de callback 

Após o usuário confirmar a autorização de uso de sua conta Melhor Envio, a API do Melhor Envio irá realizar uma request para a sua aplicação contendo o code necessário para a solicitação de token. Para definir essa URL de callback basta utilizar o método ```setRedirectUri()``` passando com parâmetro a URL que será utilizada, lembrando que essa URL precisa existir e ser válida, e deve ser a mesma URL informada no cadastro de aplicativo dentro da plataforma do Melhor Envio.

```php
$provider->setRedirectUri('http://foo.com.br/callback');
```

## Visualizar URL de callback 

Para visualizar qual URL de callback o SDK está utilizando, basta utilizar o método ```getRedirectUri()```.

```php
$provider->getRedirectUri();
```

## Refresh Token

Após 30 dias o seu token irá expirar. Mas não se preocupe, o pacote oferece o método de refresh token para que você deixe sua aplicação preparada para quando isso acontecer.

Como essas informações foram geradas anteriormente, você irá utilizar o método ```refreshToken()``` passando como parâmetro o dado respectivo, tendo como resposta um novo token.

```php
$newAuthData = $provider->refreshToken($authData['refresh_token']);
```

## Visualizar Endpoint 

Para visualizar o endpoint usado no SDK para utilizar o método ```getEndpoint()```

```php
$newAuthData = $provider->getEndpoint();
```

## Ambientes

O SDK funciona em ambos ambientes do Melhor Envio, Produção e Sandbox, para isso funcionar de forma adequeada, é necessário informar qual ambiente está sendo utilizado, por padrão o ambiente usado é o ambiente de sandbox, para fazer a troca de ambiente basta utilizar o método  ```setEnvironment()``` passando como parâmetro uma string informando o ambiente (sandbox ou production).

```php
$newAuthData = $provider->setEnvironment('production');
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
- [Vinícius Tessmann](https://github.com/viniciustessmann)
- [Thyago Santos](https://github.com/tsantos8080)

## Licença

Melhor Envio. Consulte [Arquivo de lincença](LICENSE.md) para mais informações.

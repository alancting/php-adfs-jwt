[![Packagist](https://img.shields.io/packagist/v/alancting/php-adfs-jwt?style=for-the-badge)](https://packagist.org/packages/alancting/php-adfs-jwt)
[![GitHub](https://img.shields.io/github/v/release/alancting/php-adfs-jwt?label=GitHub&style=for-the-badge)](https://github.com/alancting/php-adfs-jwt)
[![Test](https://img.shields.io/github/workflow/status/alancting/php-adfs-jwt/PHP%20Composer?label=TEST&style=for-the-badge)](https://github.com/alancting/php-adfs-jwt)
[![Coverage Status](https://img.shields.io/coveralls/github/alancting/php-adfs-jwt/master?style=for-the-badge)](https://coveralls.io/github/alancting/php-adfs-jwt?branch=master)
[![GitHub license](https://img.shields.io/github/license/alancting/php-adfs-jwt?color=blue&style=for-the-badge)](https://github.com/alancting/php-adfs-jwt/blob/master/LICENCE)  
[![firebase/php-jwt Version](https://img.shields.io/static/v1?label=firebase%2Fphp-jwt&message=5.2.0&color=blue&style=for-the-badge)](https://github.com/firebase/php-jwt/tree/v5.2.0)

# PHP-ADFS-JWT

**No longer maintained.**  
**Please moved to https://github.com/alancting/php-microsoft-jwt**

A simple library to encode and decode Microsoft Active Directory Federation Services ([ADFS](https://docs.microsoft.com/en-us/windows-server/identity/ad-fs/ad-fs-overview)) JSON Web Tokens (JWT) in PHP, conforming to [RFC 7519](https://tools.ietf.org/html/rfc7519).

**Forked From [firebase/php-jwt](https://github.com/firebase/php-jwt)**


## Installation

Use composer to manage your dependencies and download PHP-ADFS-JWT:

```bash
composer require alancting/php-adfs-jwt
```

## Example

```php
<?php

use Alancting\Adfs\JWT\Adfs\AdfsConfiguration;
use Alancting\Adfs\JWT\Adfs\AdfsAccessTokenJWT;
use Alancting\Adfs\JWT\Adfs\AdfsIdTokenJWT;

$openid_configuration_url = 'https://[Your ADFS hostname]/adfs/.well-known/openid-configuration';
$client_id = 'your_client_id';

/**
 * AdfsConfiguration will fetch the issuers, audiences and jwks for jwt validation
 */
$adfs_configs = new AdfsConfiguration($openid_configuration_url, $client_id);

$id_token_jwt = 'id.token.jwt';
$access_token_jwt = 'access.token.jwt';

/**
 * If the jwt is invalid, exception will be thrown.
 */
$access_token = new AdfsAccessTokenJWT($adfs_configs, $access_token_jwt);
echo "\n";
// Getting the payload from access token
print_r($access_token->getPayload());
echo "\n";

$id_token = new AdfsIdTokenJWT($adfs_configs, $id_token_jwt);
echo "\n";
// Getting the unique_name(username) from id token
echo $id_token->getUsername();
echo "\n";
// Getting the payload from id token
print_r($id_token->getPayload());
echo "\n";

/**
 * You might want to 'cache' the tokens for expire validation
 * To check whether the access token and id token are expired, simply call
 */
echo ($access_token->isExpired()) ? 'Access token is expired' : 'Access token is valid';
echo ($id_token->isExpired()) ? 'Id token is expired' : 'Id token is valid';
```

## Tests

Run the tests using phpunit:

```bash
$ composer run test
```

## License

[3-Clause BSD](http://opensource.org/licenses/BSD-3-Clause).

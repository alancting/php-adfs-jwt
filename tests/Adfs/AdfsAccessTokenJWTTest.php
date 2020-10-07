<?php

namespace Alancting\Adfs\Tests\Adfs;

use PHPUnit\Framework\TestCase;
use Alancting\Adfs\JWT\Adfs\AdfsConfiguration;
use Alancting\Adfs\JWT\Adfs\AdfsAccessTokenJWT;

use Alancting\Adfs\JWT\JWT;
use Alancting\Adfs\JWT\JWK;

class AdfsAccessTokenJWTTest extends TestCase
{
    private $adfs_config;
    private $jwks;
    private $private_key;

    protected function setUp(): void
    {
        $this->adfs_config = new AdfsConfiguration(
            '/../../tests/metadata/configuration/configuration.json',
            'client-id'
        );

        $jwkSet = json_decode(
            file_get_contents(__DIR__ . '/../metadata/configuration/jwks_uri.json'),
            true
        );
        $this->jwks = JWK::parseKeySet($jwkSet);

        $this->private_key = file_get_contents(__DIR__ . '/../metadata/configuration/private.pem');
    }
    
    public function testValidAccessToken()
    {
        $payload = [
          "iss" => 'http://your_domain/adfs/services/trust',
          "aud" => 'client-id',
          "exp" => time()+10000,
        ];
        $access_token = JWT::encode($payload, $this->private_key, 'RS256', '2lEZNsDIjsBPH94_b7-1z1IvnybfzOIz0hsBamzxCWc');
        $access_token_jwt = new AdfsAccessTokenJWT($this->adfs_config, $access_token);

        $this->assertFalse($access_token_jwt->isExpired());
        $this->assertEquals((object) $payload, $access_token_jwt->getPayload());
        $this->assertEquals($access_token, $access_token_jwt->getJWT());
    }

    public function testInValidAccessTokenMissingIssuer()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'Missing issuer'
        );

        $payload = [
          "aud" => 'client-id',
          "exp" => time()+10000
        ];
        $access_token = JWT::encode($payload, $this->private_key, 'RS256', '2lEZNsDIjsBPH94_b7-1z1IvnybfzOIz0hsBamzxCWc');
        $access_token_jwt = new AdfsAccessTokenJWT($this->adfs_config, $access_token);
    }

    public function testInvalidAccessTokenInvalidIssuer()
    {
        $this->setExpectedException(
            'UnexpectedValueException',
            'Invalid issuer'
        );
        $payload = [
          "iss" => 'http://wrong_domain/adfs/services/trust',
          "aud" => 'client-id',
          "exp" => time()+10000,
        ];
        $access_token = JWT::encode($payload, $this->private_key, 'RS256', '2lEZNsDIjsBPH94_b7-1z1IvnybfzOIz0hsBamzxCWc');
        $access_token_jwt = new AdfsAccessTokenJWT($this->adfs_config, $access_token);
    }

    public function testValidAccessTokenMissingAudience()
    {
        // $this->setExpectedException(
        //     'UnexpectedValueException',
        //     'Invalid audience'
        // );

        $payload = [
          "iss" => 'http://your_domain/adfs/services/trust',
          "exp" => time()+10000,
        ];
        $access_token = JWT::encode($payload, $this->private_key, 'RS256', '2lEZNsDIjsBPH94_b7-1z1IvnybfzOIz0hsBamzxCWc');
        $access_token_jwt = new AdfsAccessTokenJWT($this->adfs_config, $access_token);


        $this->assertFalse($access_token_jwt->isExpired());
        $this->assertEquals((object) $payload, $access_token_jwt->getPayload());
        $this->assertEquals($access_token, $access_token_jwt->getJWT());
    }
    
    public function testValidAccessTokenInvalidAudience()
    {
        // $this->setExpectedException(
        //     'UnexpectedValueException',
        //     'Invalid audience'
        // );

        $payload = [
          "iss" => 'http://your_domain/adfs/services/trust',
          "aud" => 'wrong-client-id',
          "exp" => time()+10000,
        ];
        $access_token = JWT::encode($payload, $this->private_key, 'RS256', '2lEZNsDIjsBPH94_b7-1z1IvnybfzOIz0hsBamzxCWc');
        $access_token_jwt = new AdfsAccessTokenJWT($this->adfs_config, $access_token);


        $this->assertFalse($access_token_jwt->isExpired());
        $this->assertEquals((object) $payload, $access_token_jwt->getPayload());
        $this->assertEquals($access_token, $access_token_jwt->getJWT());
    }

    public function setExpectedException($exceptionName, $message = '', $code = null)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exceptionName);
        } else {
            parent::setExpectedException($exceptionName, $message, $code);
        }
    }
}
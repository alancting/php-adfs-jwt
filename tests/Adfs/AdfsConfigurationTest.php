<?php

namespace Alancting\Adfs\Tests\Adfs;

use PHPUnit\Framework\TestCase;
use Alancting\Adfs\JWT\Adfs\AdfsConfiguration;

class AdfsConfigurationTest extends TestCase
{
    public function testConstructor()
    {
        $config_url = '/../../tests/metadata/configuration/configuration.json';
        $client_id = 'client-id';
        
        $config = new AdfsConfiguration($config_url, $client_id);

        $this->assertEquals($config->getClientId(), $client_id);
        $this->assertArrayHasKey('2lEZNsDIjsBPH94_b7-1z1IvnybfzOIz0hsBamzxCWc', $config->getJWKs());
        $this->assertEquals($config->getIssuer(), 'https://your_domain/adfs');
        $this->assertEquals($config->getAccessTokenIssuer(), 'http://your_domain/adfs/services/trust');
        $this->assertEquals($config->getIdTokenSigingAlgValuesSupported(), ["RS256"]);
        $this->assertEquals($config->getTokenEndpointAuthSigingAlgValuesSupported(), ["RS256"]);
    }
}
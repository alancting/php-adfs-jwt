<?php

namespace Alancting\Adfs\JWT\Adfs;

use Alancting\Adfs\JWT\JWK;

class AdfsConfiguration
{
    private $config_uri;
    private $client_id;

    private $authorization_endpoint;
    private $token_endpoint;
    private $userinfo_endpoint;
    private $device_authorization_endpoint;
    private $end_session_endpoint;
    
    private $jwks_uri;

    private $issuer;
    private $access_token_issuer;
    
    private $id_token_signing_alg_values_supported;
    private $token_endpoint_auth_signing_alg_values_supported;
    
    private $jwks;

    public function __construct($config_uri, $client_id)
    {
        $this->config_uri = $config_uri;
        $this->client_id = $client_id;
        $this->_load();
    }

    public function getJWKs()
    {
        return $this->jwks;
    }
    
    public function getClientId()
    {
        return $this->client_id;
    }
    
    public function getIssuer()
    {
        return $this->issuer;
    }

    public function getAccessTokenIssuer()
    {
        return $this->access_token_issuer;
    }

    public function getIdTokenSigingAlgValuesSupported()
    {
        return $this->id_token_signing_alg_values_supported;
    }

    public function getTokenEndpointAuthSigingAlgValuesSupported()
    {
        return $this->token_endpoint_auth_signing_alg_values_supported;
    }

    private function _load()
    {
        $json = $this->getFromUrlOrFile($this->config_uri);
        $data = json_decode($json, true);
        
        $this->authorization_endpoint = $data['authorization_endpoint'];
        $this->token_endpoint = $data['token_endpoint'];
        $this->userinfo_endpoint = $data['userinfo_endpoint'];
        $this->device_authorization_endpoint = $data['device_authorization_endpoint'];
        $this->end_session_endpoint = $data['end_session_endpoint'];
        $this->jwks_uri = $data['jwks_uri'];
        $this->issuer = $data['issuer'];
        $this->access_token_issuer = $data['access_token_issuer'];
        $this->id_token_signing_alg_values_supported = $data['id_token_signing_alg_values_supported'];
        $this->token_endpoint_auth_signing_alg_values_supported = $data['token_endpoint_auth_signing_alg_values_supported'];
        
        $jwks_json = $this->getFromUrlOrFile($this->jwks_uri);
        $jwks_data = json_decode($jwks_json, true);

        $this->jwks = JWK::parseKeySet($jwks_data);
    }

    private function getFromUrlOrFile($value)
    {
        return file_get_contents(
            filter_var($value, FILTER_VALIDATE_URL) ? $value : __DIR__  . $value
        );
    }
}
<?php

namespace Alancting\Adfs\JWT\Adfs;

use Alancting\Adfs\JWT\JWT;
use \UnexpectedValueException;

/**
 * Following
 * https://docs.microsoft.com/en-us/azure/active-directory/develop/access-tokens#validating-tokens
 */
abstract class AbstractAdfsJWT
{
    private $adfs_configuration;
    private $jwt;
    private $payload;

    abstract protected function getIssuer();
    abstract protected function getAllowedAlgs();
    abstract protected function isAudienceValid($payload);

    protected function __construct($adfs_configuration)
    {
        $this->adfs_configuration = $adfs_configuration;
    }

    public function isExpired()
    {
        return JWT::isExpired($this->payload);
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getJWT()
    {
        return $this->jwt;
    }

    protected function decode($jwt, $allowed_algs)
    {
        $this->jwt = $jwt;
        
        $payload = JWT::decode(
            $jwt,
            $this->getConfiguration()->getJWKs(),
            array_merge($this->getAllowedAlgs(), $allowed_algs)
        );
        
        $this->_validateIssuer($payload);
        $this->_validateAudience($payload);
        
        $this->payload = $payload;
    }

    protected function getConfiguration()
    {
        return $this->adfs_configuration;
    }
    
    private function _validateIssuer($payload)
    {
        if (!isset($payload->iss)) {
            throw new UnexpectedValueException('Missing issuer');
        }
        if ($payload->iss !== $this->getIssuer()) {
            throw new UnexpectedValueException('Invalid issuer');
        }
    }

    private function _validateAudience($payload)
    {
        if (!isset($payload->aud) && !$this->isAudienceValid($payload)) {
            throw new UnexpectedValueException('Missing audience');
        }
        if (!$this->isAudienceValid($payload)) {
            throw new UnexpectedValueException('Invalid audience');
        }
    }
}
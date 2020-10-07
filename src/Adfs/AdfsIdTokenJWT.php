<?php

namespace Alancting\Adfs\JWT\Adfs;

use Alancting\Adfs\JWT\Adfs\AbstractAdfsJWT;

class AdfsIdTokenJWT extends AbstractAdfsJWT
{
    public function __construct($adfs_configuration, $id_token, $allowed_algs = [])
    {
        parent::__construct($adfs_configuration);
        $this->decode($id_token, $allowed_algs);
    }

    public function getUsername()
    {
        return $this->getPayload()->unique_name;
    }

    protected function getIssuer()
    {
        return $this->getConfiguration()->getIssuer();
    }

    protected function getAllowedAlgs()
    {
        return $this->getConfiguration()->getIdTokenSigingAlgValuesSupported();
    }

    protected function isAudienceValid($payload)
    {
        return (isset($payload->aud) && $payload->aud === $this->getConfiguration()->getClientId());
    }
}
<?php

namespace Alancting\Adfs\JWT\Adfs;

use Alancting\Adfs\JWT\Adfs\AbstractAdfsJWT;

class AdfsAccessTokenJWT extends AbstractAdfsJWT
{
    public function __construct($adfs_configuration, $id_token, $allowed_algs = [])
    {
        parent::__construct($adfs_configuration);
        $this->decode($id_token, $allowed_algs);
    }
   
    protected function getIssuer()
    {
        return $this->getConfiguration()->getAccessTokenIssuer();
    }

    protected function getAllowedAlgs()
    {
        return $this->getConfiguration()->getTokenEndpointAuthSigingAlgValuesSupported();
    }

    protected function isAudienceValid($payload)
    {
        return true;
    }
}
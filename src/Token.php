<?php

namespace Onetoweb\Shopify;

/**
 * Token.
 */
class Token
{
    /**
     * @param string $accessToken
     * @param array $scope
     */
    public function __construct(string $accessToken, array $scope)
    {
        $this->accessToken = $accessToken;
        $this->scope = $scope;
    }
    
    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
    
    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->scope;
    }
}
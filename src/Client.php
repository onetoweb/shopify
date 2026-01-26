<?php

namespace Onetoweb\Shopify;

use Onetoweb\Shopify\Endpoint\Endpoints;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client as GuzzleCLient;
use Onetoweb\Shopify\Token;
use Onetoweb\Shopify\Exception\TokenException;

/**
 * Shopify Api Client.
 */
#[\AllowDynamicProperties]
class Client
{
    /**
     * Urls.
     */
    public const AUTHORIZATION_URL = 'https://%s.myshopify.com/admin/oauth/authorize';
    public const ACCESS_TOKEN_URL = 'https://%s.myshopify.com/admin/oauth/access_token';
    public const API_URL = 'https://%s.myshopify.com/admin/api/%s/graphql.json';
    
    /**
     * @var string
     */
    private $shop;
    
    /**
     * @var string
     */
    private $clientId;
    
    /**
     * @var string
     */
    private $secret;
    
    /**
     * @var string
     */
    private $version;
    
    /**
     * @var Token
     */
    private $token;
    
    /**
     * @var callable
     */
    private $updateTokenCallback;
    
    /**
     * @param string $shop
     * @param string $apiKey
     * @param string $secret
     * @param string $version = '2026-01'
     */
    public function __construct(string $shop, string $clientId, string $secret, string $version = '2026-01')
    {
        $this->shop = $shop;
        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->version = $version;
        
        // load endpoints
        $this->loadEndpoints();
    }
    
    /**
     * @return void
     */
    private function loadEndpoints(): void
    {
        foreach (Endpoints::list() as $name => $class) {
            $this->{$name} = new $class($this);
        }
    }
    
    /**
     * @param callable $updateTokenCallback
     * 
     * @return void
     */
    public function setUpdateTokenCallback(callable $updateTokenCallback): void
    {
        $this->updateTokenCallback = $updateTokenCallback;
    }
    
    /**
     * @param Token $token
     * 
     * @return void
     */
    public function setToken(Token $token): void
    {
        $this->token = $token;
    }
    
    /**
     * @return Token|null
     */
    public function getToken(): ?Token
    {
        return $this->token;
    }
    
    /**
     * @param callable $updateTokenCallback
     * 
     * @return void
     */
    public function updateToken(array $tokenArray): void
    {
        // set token
        $this->token = new Token(
            $tokenArray['access_token'],
            explode(',', $tokenArray['scope'])
        );
        
        // call update token callback
        ($this->updateTokenCallback)($this->token);
    }
    
    /**
     * @param array $scope
     * @param string $redirectUrl
     * @param string $nonce
     * @param bool $expiring = false
     * 
     * @return string
     */
    public function getAuthorizationUrl(array $scope, string $redirectUrl, string $nonce, bool $expiring = false): string
    {
        return sprintf(self::AUTHORIZATION_URL, $this->shop).'?'.http_build_query([
            'client_id' => $this->clientId,
            'scope' => implode(',', $scope),
            'redirect_uri' => $redirectUrl,
            'state' => $nonce,
            'expiring' => (string) ($expiring ? 1 : 0)
        ]);
    }
    
    /**
     * @return string
     */
    public function getAccessTokenUrl(): string
    {
        return sprintf(self::ACCESS_TOKEN_URL, $this->shop);
    }
    
    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return sprintf(self::API_URL, $this->shop, $this->version);
    }
    
    /**
     * @param string $code
     * 
     * @return void
     */
    public function getAccessToken(string $code): void
    {
        // build options
        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::FORM_PARAMS => [
                'client_id' => $this->clientId,
                'client_secret' => $this->secret,
                'code' => $code,
            ],
        ];
        
        // make request
        $response = (new GuzzleCLient())->post($this->getAccessTokenUrl(), $options);
        
        // decode json
        $tokenArray = json_decode($response->getBody()->getContents(), true);
        
        $this->updateToken($tokenArray);
    }
    
    /**
     * Clears line endings and duplicate spaces
     * 
     * @param string $graph
     * 
     * @return string
     */
    public function cleanGraph(string $graph): string
    {
        return trim(preg_replace('/\s+/', ' ', str_replace(PHP_EOL, ' ', $graph)));
    }
    
    /**
     * @param string $graph
     * @param array $variables = []
     * 
     * @return array|NULL
     */
    public function request(string $graph, array $variables = []): ?array
    {
        if (!$this->token instanceof Token) {
            throw new TokenException('token not set use Client::setToken, or use the oAuth workflow to request a token');
        }
        
        // build json
        $data = [
            'query' => $this->cleanGraph($graph)
        ];
        
        // add variables
        if (count($variables) > 0) {
            $data['variables'] = $variables;
        }
        
        // build options
        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'x-shopify-access-token' => $this->token->getAccessToken(),
            ],
            RequestOptions::JSON => $data
        ];
        
        // make request
        $response = (new GuzzleCLient())->post($this->getApiUrl(), $options);
        
        // decode json
        $json = json_decode($response->getBody()->getContents(), true);
        
        return $json;
    }
}

.. title:: Index

Index
=====

.. contents::
    :local:

===========
Basic Usage
===========

Setup

.. code-block:: php
    
    require 'vendor/autoload.php';
    
    use Onetoweb\Shopify\{Client, Token};
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Request;
    
    // start session
    $session = new Session();
    $session->start();
    
    // param
    $shop = '{shop_name}';
    $clientId = '{client_id}';
    $secret = '{secret}';
    
    // (optional) param
    $version = '2026-01';
    
    // setup client
    $client = new Client($shop, $clientId, $secret, $version);
    
    // set update token callback
    $client->setUpdateTokenCallback(function(Token $token) use ($session) {
        
        // store token
        $session->set('token', [
            'access_token' => $token->getAccessToken(),
            'scope' => $token->getScope(),
        ]);
    });
    
    /**
     * oAuth workflow.
     */
    
    // get request
    $request = Request::createFromGlobals();
    
    if ($session->has('token')) {
        
        // load token from storage
        $tokenArray = $session->get('token');
        
        $token = new Token(
            $tokenArray['access_token'],
            $tokenArray['scope']
        );
        
        $client->setToken($token);
        
    } elseif ($request->get('code')) {
        
        // check nonce
        if ($request->get('state') !== $session->get('nonce')) {
            
            throw new \Exception('states do not match');
        }
        
        $client->getAccessToken($request->get('code'));
        
    } elseif (!$client->getToken()) {
        
        // auth param
        $scope = [
            'read_products',
            'read_inventory',
            'read_product_listings',
        ];
        $redirectUrl = 'https://example.com/';
        $nonce = bin2hex(random_bytes(32));
        
        // store nonce
        $session->set('nonce', $nonce);
        
        // (optional, default: false)
        $expiring = false;
        
        // get authorization url
        $authorizationUrl = $client->getAuthorizationUrl($scope, $redirectUrl, $nonce, $expiring);
        
        // display auth url
        echo '<a href="'.$authorizationUrl.'">Authorize</a>';
    }

==============================
Example authorisation template
==============================

`Authorize </authorize.html>`_

=========
Endpoints
=========

* `Product <product.rst>`_
* `Catalog <catalog.rst>`_

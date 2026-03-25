<?php

namespace Onetoweb\Shopify\Endpoint;

use Onetoweb\Shopify\Client;

/**
 * Abstract Endpoint.
 */
abstract class AbstractEndpoint implements EndpointInterface
{
    /**
     * @var Client
     */
    protected $client;
    
    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * @param string $type
     * @param string $graph
     */
    protected function fetchAll(string $type, string $graph)
    {
        $cursor = null;
        $continue = false;
        
        do {
            
            $after = $cursor !== null ? 'after: "'.$cursor.'"' : '';
            
            $result = $this->client->request(sprintf($graph, $after));
            
            $continue = (
                isset($result['data'][$type]['pageInfo']['hasNextPage'])
                and $result['data'][$type]['pageInfo']['hasNextPage']
            );
            
            foreach ($result['data'][$type]['nodes'] as $product) {
                yield $product;
            }
            
            $cursor = $result['data'][$type]['pageInfo']['endCursor'];
            
            
        } while ($continue);
    }
}

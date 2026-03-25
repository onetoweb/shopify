<?php

namespace Onetoweb\Shopify\Endpoint\Endpoints;

use Onetoweb\Shopify\Endpoint\AbstractEndpoint;
use Onetoweb\Shopify\Graph\Graphs\Product as ProductGraph;
use Generator;

/**
 * Product Endpoint.
 */
class Product extends AbstractEndpoint
{
    /**
     * @param string $query = ''
     * 
     * @return array|NULL
     */
    public function count(string $query = ''): ?array
    {
        return $this->client->request(<<<GRAPH
query GetProducts {
    productsCount(query: "$query") {
        count
    }
}
GRAPH
);
    }
    
    /**
     * @param $first = 100
     * 
     * @return array|NULL
     */
    public function listFirst($first = 100): ?array
    {
        $full = ProductGraph::full();
        
        return $this->client->request(<<<GRAPH
query GetProducts {
    products(first: $first) {
        
        nodes $full
    }
}
GRAPH
);

    }
    
    /**
     * @param string $id
     * 
     * @return array|NULL
     */
    public function get(string $id): ?array
    {
        $full = ProductGraph::full();
        
        return $this->client->request(<<<GRAPH
query {
    product(id: "$id") $full
}
GRAPH
            );
    }
    
    /**
     * @return Generator
     */
    public function listAllInventory(int $first = 100): Generator
    {
        $inventory = ProductGraph::inventory();
        
        // setup graph
        $graph = <<<GRAPH
query GetProducts {
    products(first: $first, %s) {
        nodes $inventory
        pageInfo {
            hasPreviousPage
            hasNextPage
            startCursor
            endCursor
        }
    }
}
GRAPH;
        
        $cursor = null;
        $continue = false;
        
        do {
            
            $after = $cursor !== null ? 'after: "'.$cursor.'"' : '';
            
            $result = $this->client->request(sprintf($graph, $after));
            
            $continue = (
                isset($result['data']['products']['pageInfo']['hasNextPage'])
                and $result['data']['products']['pageInfo']['hasNextPage']
                );
            
            foreach ($result['data']['products']['nodes'] as $product) {
                yield $product;
            }
            
            $cursor = $result['data']['products']['pageInfo']['endCursor'];
            
            
        } while ($continue);
    }
    
    /**
     * @return Generator
     */
    public function listAll(int $first = 100): Generator
    {
        $full = ProductGraph::full();
        
        // setup graph
        $graph = <<<GRAPH
query GetProducts {
    products(first: $first, %s) {
        nodes $full
        pageInfo {
            hasPreviousPage
            hasNextPage
            startCursor
            endCursor
        }
    }
}
GRAPH;
        
        $cursor = null;
        $continue = false;
        
        do {
            
            $after = $cursor !== null ? 'after: "'.$cursor.'"' : '';
            
            $result = $this->client->request(sprintf($graph, $after));
            
            $continue = (
                isset($result['data']['products']['pageInfo']['hasNextPage'])
                and $result['data']['products']['pageInfo']['hasNextPage']
            );
            
            foreach ($result['data']['products']['nodes'] as $product) {
                yield $product;
            }
            
            $cursor = $result['data']['products']['pageInfo']['endCursor'];
            
            
        } while ($continue);
    }
}

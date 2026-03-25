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
        $graph = <<<GRAPH
query GetProducts {
    productsCount(query: "$query") {
        count
    }
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param $first = 100
     * 
     * @return array|NULL
     */
    public function listFirst($first = 100): ?array
    {
        $full = ProductGraph::full();
        
        $graph = <<<GRAPH
query GetProducts {
    products(first: $first) {
        nodes $full
    }
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param string $id
     * 
     * @return array|NULL
     */
    public function get(string $id): ?array
    {
        $full = ProductGraph::full();
        
        $graph = <<<GRAPH
query {
    product(id: "$id") $full
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param int $first = 100
     * 
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
        
        foreach ($this->fetchAll('products', $graph) as $product) {
            yield $product;
        }
    }
    
    /**
     * @param int $first = 100
     * 
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
        
        foreach ($this->fetchAll('products', $graph) as $product) {
            yield $product;
        }
    }
}

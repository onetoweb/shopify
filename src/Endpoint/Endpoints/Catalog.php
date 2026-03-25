<?php

namespace Onetoweb\Shopify\Endpoint\Endpoints;

use Onetoweb\Shopify\Endpoint\AbstractEndpoint;
use Onetoweb\Shopify\Graph\Graphs\Catalog as CatalogGraph;
use Generator;

/**
 * Catalog Endpoint.
 */
class Catalog extends AbstractEndpoint
{
    /**
     * @param string $query = ''
     * 
     * @return array|NULL
     */
    public function count(string $query = ''): ?array
    {
        $graph = <<<GRAPH
query GetCatalogs {
    catalogsCount(query: "$query") {
        count
    }
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param int $first = 250
     * 
     * @return array|NULL
     */
    public function first(int $first = 250): ?array
    {
        $full = CatalogGraph::full();
        
        $graph = <<<GRAPH
{
    catalogs(first:$first) {
        
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
        $full = CatalogGraph::full();
        
        $graph = <<<GRAPH
query {
    catalog(id: "$id") $full
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param int $first = 100
     * 
     * @return Generator
     */
    public function listAll(int $first = 100): Generator
    {
        $full = CatalogGraph::full();
        
        // setup graph
        $graph = <<<GRAPH
query GetCatalogs {
    catalogs(first: $first, %s) {
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
        
        foreach ($this->fetchAll('catalogs', $graph) as $product) {
            yield $product;
        }
    }
}

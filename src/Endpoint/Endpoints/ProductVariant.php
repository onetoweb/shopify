<?php

namespace Onetoweb\Shopify\Endpoint\Endpoints;

use Onetoweb\Shopify\Endpoint\AbstractEndpoint;
use Onetoweb\Shopify\Graph\Graphs\ProductVariant as ProductVariantGraph;
use Generator;

/**
 * Product Variant Endpoint.
 */
class ProductVariant extends AbstractEndpoint
{
    /**
     * @param string $query = ''
     *
     * @return array|NULL
     */
    public function count(string $query = ''): ?array
    {
        $graph = <<<GRAPH
query GetProductVariants {
    productVariantsCount(query: "$query") {
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
        $full = ProductVariantGraph::full();
        
        $graph = <<<GRAPH
query ProductVariantsList {
    productVariants(first: $first) {
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
        $full = ProductVariantGraph::full();
        
        $graph = <<<GRAPH
query {
    productVariant(id: "$id") $full
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
        $full = ProductVariantGraph::full();
        
        // setup graph
        $graph = <<<GRAPH
query GetProductVariants {
    productVariants(first: $first, %s) {
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
        
        foreach ($this->fetchAll('productVariants', $graph) as $product) {
            yield $product;
        }
    }
}

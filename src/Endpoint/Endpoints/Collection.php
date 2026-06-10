<?php

namespace Onetoweb\Shopify\Endpoint\Endpoints;

use Onetoweb\Shopify\Endpoint\AbstractEndpoint;
use Onetoweb\Shopify\Graph\Graphs\Collection as CollectionGraph;
use Generator;

/**
 * Collection Endpoint.
 */
class Collection extends AbstractEndpoint
{
    /**
     * @param string $query = ''
     * 
     * @return array|NULL
     */
    public function count(string $query = ''): ?array
    {
        $graph = <<<GRAPH
query GetCollections {
    collectionsCount(query: "$query") {
        count
    }
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @return array|NULL
     */
    public function struct(): ?array
    {
        /**
         * @param array $collection
         * @param array $foundCollections
         * 
         * @return array
         */
        $getSubcollections = function(array $collection, array &$foundCollections) use (&$getSubcollections) : array {
            
            $subcollectionIds = json_decode($collection['metafield']['value']);
            
            $subcollections = [];
            if ($subcollectionIds !== null) {
                
                foreach ($subcollectionIds as $subcollectionId) {
                    
                    $subcollection = null;
                    if (isset($foundCollections[$subcollectionId])) {
                        
                        $subcollection = $foundCollections[$subcollectionId];
                        
                    } else {
                        
                        $foundSubcollection = $this->getSlim($subcollectionId);
                        
                        if (isset($foundSubcollection['data']['collection'])) {
                            
                            $subcollection = $foundSubcollection['data']['collection'];
                            
                            $foundCollections[$subcollectionId] = $subcollection;
                        }
                    }
                    
                    if ($subcollection !== null) {
                        
                        $subcollection['subcollections'] = $getSubcollections($subcollection, $foundCollections);
                        
                        $subcollections[] = $subcollection;
                    }
                }
            }
            
            return $subcollections;
        };
        
        $foundCollections = [];
        $struct = [];
        
        foreach ($this->listAllSlim() as $collection) {
            
            $collection['subcollections'] = $getSubcollections($collection, $foundCollections);
            
            $struct[] = $collection;
        }
        
        return $struct;
    }
    
    /**
     * @param int $first = 250
     * 
     * @return array|NULL
     */
    public function first(int $first = 250): ?array
    {
        $full = CollectionGraph::full();
        
        $graph = <<<GRAPH
query GetCollections {
    collections(first: $first) {
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
        $full = CollectionGraph::full();
        
        $graph = <<<GRAPH
query {
    collection(id: "$id") $full
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param string $id
     *
     * @return array|NULL
     */
    public function getSlim(string $id): ?array
    {
        $full = CollectionGraph::slim();
        
        $graph = <<<GRAPH
query {
    collection(id: "$id") $full
}
GRAPH;
        
        return $this->client->request($graph);
    }
    
    /**
     * @param int $first = 100
     *
     * @return Generator
     */
    public function listAllSlim(int $first = 100): Generator
    {
        $slim = CollectionGraph::slim();
        
        // setup graph
        $graph = <<<GRAPH
query GetCollections {
    collections(first: $first, %s) {
        nodes $slim
        pageInfo {
            hasPreviousPage
            hasNextPage
            startCursor
            endCursor
        }
    }
}
GRAPH;
        
        foreach ($this->fetchAll('collections', $graph) as $product) {
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
        $full = CollectionGraph::full();
        
        // setup graph
        $graph = <<<GRAPH
query GetCollections {
    collections(first: $first, %s) {
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
        
        foreach ($this->fetchAll('collections', $graph) as $product) {
            yield $product;
        }
    }
}

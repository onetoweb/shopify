<?php

namespace Onetoweb\Shopify\Graph\Graphs;

use Onetoweb\Shopify\Graph\AbstractGraph;

/**
 * Collection Endpoint.
 */
class Collection extends AbstractGraph
{
    /**
     * @param array $extra = []
     * 
     * @return string
     */
    public static function full(array $extra = []): string
    {
        $extraGraph = implode(PHP_EOL, $extra);
        
        return <<<GRAPH
{
            id
            title
            handle
            description
            productsCount {
                count
            }
            image {
                id
                url
            }
            metafield(key: "parent_collections", namespace: "collection_info") {
                value
            }
            $extraGraph
            updatedAt
        }
GRAPH;
    }
    
    /**
     * @param array $extra = []
     *
     * @return string
     */
    public static function slim(array $extra = []): string
    {
        $extraGraph = implode(PHP_EOL, $extra);
        
        return <<<GRAPH
{
            id
            title
            productsCount {
                count
            }
            metafield(key: "subcollections", namespace: "collection_info") {
                value
            }
            $extraGraph
        }
GRAPH;
    }
}

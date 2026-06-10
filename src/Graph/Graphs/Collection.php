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
            metafield(key: "subcollections", namespace: "collection_info") {
                id
                key
                type
                createdAt
                value
            }
            $extraGraph
            updatedAt
        }
GRAPH;
    }
}

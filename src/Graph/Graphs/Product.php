<?php

namespace Onetoweb\Shopify\Graph\Graphs;

use Onetoweb\Shopify\Graph\AbstractGraph;

/**
 * Product Endpoint.
 */
class Product extends AbstractGraph
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
            totalInventory
            tracksInventory
            status
            productType
            description
            images(first: 250) {
                edges {
                    node {
                        url
                    }
                }
            }
            category {
                name
            }
            status
            priceRangeV2 {
                minVariantPrice {
                    amount
                }
                maxVariantPrice {
                    amount
                }
            }
            $extraGraph
        }
GRAPH;
    }
    
    /**
     * @param array $extra = []
     *
     * @return string
     */
    public static function inventory(array $extra = []): string
    {
        $extraGraph = implode(PHP_EOL, $extra);
        
        return <<<GRAPH
{
                id
                title
                totalInventory
                tracksInventory
        }
GRAPH;
    }
}

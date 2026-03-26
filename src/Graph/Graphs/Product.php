<?php

namespace Onetoweb\Shopify\Graph\Graphs;

use Onetoweb\Shopify\Graph\AbstractGraph;
use Onetoweb\Shopify\Graph\Graphs\{Collection as CollectionGraph};
use Onetoweb\Shopify\Graph\Graphs\{ProductVariant as ProductVariantGraph};

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
        
        $variantGraph = ProductVariantGraph::full();
        $collectionGraph = CollectionGraph::full();
        
        return <<<GRAPH
{
            id
            title
            handle
            totalInventory
            tracksInventory
            status
            productType
            description
            descriptionHtml
            onlineStoreUrl
            vendor
            status
            isGiftCard
            requiresSellingPlan
            tags
            seo {
                title
                description
            }
            images(first: 250) {
                edges {
                    node {
                        url
                    }
                }
            }
            media(first: 250) {
                edges {
                    node {
                        id
                        mediaContentType
                    }
                }
            }
            category {
                id
                name
            }
            priceRangeV2 {
                minVariantPrice {
                    amount
                    currencyCode
                }
                maxVariantPrice {
                    amount
                    currencyCode
                }
            }
            collections(first: 250) {
                edges {
                    node $collectionGraph
                }
            }
            variants(first: 250) {
                edges {
                    node $variantGraph
                }
            }
            options(first: 250) {
                id
                name
                optionValues {
                    id
                    name
                    swatch {
                        color
                        image {
                            id
                        }
                    }
                }
            }
            sellingPlanGroups(first: 250) {
                edges {
                    node {
                        name
                    }
                }
            }
            $extraGraph
            createdAt
            updatedAt
            publishedAt
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

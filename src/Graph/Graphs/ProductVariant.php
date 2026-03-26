<?php

namespace Onetoweb\Shopify\Graph\Graphs;

use Onetoweb\Shopify\Graph\AbstractGraph;

/**
 * Product Variant Endpoint.
 */
class ProductVariant extends AbstractGraph
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
            displayName
            barcode
            sku
            price
            unitPrice {
                amount
                currencyCode
            }
            taxable
            availableForSale
            inventoryItem {
                id
                countryCodeOfOrigin
                harmonizedSystemCode
                createdAt
                measurement {
                    id
                    weight {
                        unit
                        value
                    }
                }
                unitCost {
                    amount
                    currencyCode
                }
            }
            product {
                id
                title
            }
            $extraGraph
            createdAt
            updatedAt
        }
GRAPH;
    }
}

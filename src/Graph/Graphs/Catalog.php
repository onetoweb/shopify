<?php

namespace Onetoweb\Shopify\Graph\Graphs;

use Onetoweb\Shopify\Graph\AbstractGraph;

/**
 * Catalog Endpoint.
 */
class Catalog extends AbstractGraph
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
            status
            operations {
                id
                status
                processedRowCount
                rowCount {
                    count
                    exceedsMax
                }
            }
            priceList {
                id
                name
                currency
            }
            $extraGraph
        }
GRAPH;
    }
}

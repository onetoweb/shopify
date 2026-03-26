.. _top:
.. title:: ProductVariant

`Back to index <index.rst>`_

==============
ProductVariant
==============

.. contents::
    :local:


List first product variants
```````````````````````````

.. code-block:: php
    
    $first  = 100;
    $result = $client->productVariant->listFirst($first);


Get product variants by id
``````````````````````````

.. code-block:: php
    
    $id = 'gid://shopify/ProductVariant/0000000000000';
    $result = $client->productVariant->get($id);


List all product variants
`````````````````````````

Yields collections using the Generator syntax.

.. code-block:: php
    
    foreach ($client->productVariant->listAll() as $productVariant) {
        
    }


Count product variants
``````````````````````

.. code-block:: php
    
    $queryString = '';
    $result = $client->productVariant->count($queryString);


`Back to top <#top>`_
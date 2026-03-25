.. _top:
.. title:: Catalog

`Back to index <index.rst>`_

=======
Catalog
=======

.. contents::
    :local:


List first catalogs
```````````````````

.. code-block:: php
    
    $first  = 100;
    $result = $client->catalog->listFirst($first);


Get catalog by id
`````````````````

.. code-block:: php
    
    $id = 'gid://shopify/MarketCatalog/0000000000000';
    $result = $client->catalog->get($id);


List all catalogs
`````````````````

Yields catalogs using the Generator syntax.

.. code-block:: php
    
    foreach ($client->catalog->listAll() as $catalog) {
        
    }


Count catalogs
``````````````

.. code-block:: php
    
    $queryString = '';
    $result = $client->catalog->count($queryString);


`Back to top <#top>`_
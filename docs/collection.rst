.. _top:
.. title:: Collection

`Back to index <index.rst>`_

==========
Collection
==========

.. contents::
    :local:


List first collections
``````````````````````

.. code-block:: php
    
    $first  = 100;
    $result = $client->collection->listFirst($first);


Get collection by id
````````````````````

.. code-block:: php
    
    $id = 'gid://shopify/Collection/0000000000000';
    $result = $client->collection->get($id);


List all collections
````````````````````

Yields collections using the Generator syntax.

.. code-block:: php
    
    foreach ($client->collection->listAll() as $collection) {
        
    }


Count collections
`````````````````

.. code-block:: php
    
    $queryString = '';
    $result = $client->collection->count($queryString);


`Back to top <#top>`_
.. _top:
.. title:: Product

`Back to index <index.rst>`_

=======
Product
=======

.. contents::
    :local:


List first products
```````````````````

.. code-block:: php
    
    $first  = 100;
    $result = $client->product->listFirst($first);


Get product by id
`````````````````

.. code-block:: php
    
    $id = 'gid://shopify/Product/0000000000000';
    $result = $client->product->get($id);


List all products
`````````````````

Yields products using the Generator syntax.

.. code-block:: php
    
    foreach ($client->product->listAll() as $product) {
        
    }


`Back to top <#top>`_
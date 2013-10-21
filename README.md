# LeftRightTreeTraversal version 0.2.1

## Introduction

The goal of this small library is to build a tree with left and right values on each vertex/node from a list having hierachical relations.
The classes provide some useful abilities, see below.

## Example/Usage

### Using array as input
``` php

<?php 

// here is your input data
$arrayList = array(
	array(
		'id' => 1,
		'parent' => null
	),
	array(
		'id' => 2,
		'parent' => 1
	),
	array(
		'id' => 3,
		'parent' => 1
	),
	array(
		'id' => 4,
		'parent' => 2
	)
);

$objBuilder = new TreeBuilder();
$objBuilder->setRawData($arrayList);
$objBuilder->compute();

// get the graph as array
$arrayGraph = $objBuilder->export();

// attempt to retrieve the node with the left value set to 5
$objBuilder->getNodeWithLeftValue(5)->getId();

// attempt to retrieve the node with the right value set to 7
$objBuilder->getNodeWithRightValue(7)->getId();

// attempt to retrieve an inexisting node
var_dump($objBuilder->getNodeWithLeftValue(-1));

// attempt to retrieve the root node
var_dump($objBuilder->getRootNode()->getId());
```

### Using nodes as input

``` php

<?php 

// here is your input data
$objNode1 = new Node(1);
$objNode2 = new Node(2);
$objNode3 = new Node(3);
$objNode4 = new Node(4);

$objNode1->addChild($objNode2);
$objNode1->addChild($objNode3);
$objNode2->addChild($objNode4);

$objBuilder = new TreeBuilder();
$objBuilder->addNode($objNode1);
$objBuilder->addNode($objNode2);
$objBuilder->addNode($objNode3);
$objBuilder->addNode($objNode4);

$objBuilder->compute();

// get the graph as array
$arrayGraph = $objBuilder->export();

/* ... */

```

#### Note

The above example shows a code using `Node::addChild()`.
It is also possible to use `Node::setParentNode()` :

``` php
<?php 
/* ... */

$objNode2->setParentNode($objNode1);
$objNode3->setParentNode($objNode1);
$objNode4->setParentNode($objNode2);

$objBuilder = new TreeBuilder();
$objBuilder->addNode($objNode1);
$objBuilder->addNode($objNode2);
$objBuilder->addNode($objNode3);
$objBuilder->addNode($objNode4);

/* ... */

```

### Configuration

It is possible to configure the keys used in the input array and the exported array.
By default, it is set to the following values :

* Input array:
** id: id
** parent: parent
* Exported array:
** left: left
** right: right

To override them, you just have to specify your preferences in the TreeBuilder instanciation :

``` php
<?php 

$hashConfig = array(
    'key_left'      => 'my_left',
    'key_right'     => 'my_right',
    'key_id'        => 'my_id',
    'key_parent'    => 'my_parent'
);

$objBuilder = new TreeBuilder($hashConfig);

/* ... */

```
It is particularly useful if you are storing your input data in database, with specific column names (no mapping).

## Tests
All classes have been tested, using atoum's framework. See `tests/` directory for more informations.

## Improvements
Feel free to fork this project or suggest improvement ideas. All criticisms are obviously welcomed.

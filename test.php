<?php

// temp includes
include 'LeftRightTreeTraversal/Node.php';
include 'LeftRightTreeTraversal/TreeBuilder.php';

use \LeftRightTreeTraversal\Node;
use \LeftRightTreeTraversal\TreeBuilder;

// here is a list of relations
$arrayList = array(
	array(
		'id' => 2,
		'parent' => 1
	),
	array(
		'id' => 1,
		'parent' => null
	),
	array(
		'id' => 3,
		'parent' => 2
	),
	array(
		'id' => 4,
		'parent' => 2
	),
	array(
		'id' => 5,
		'parent' => 1
	),
	array(
		'id' => 6,
		'parent' => 5
	),
	array(
		'id' => 7,
		'parent' => 5
	),
	array(
		'id' => 8,
		'parent' => 5
	),
	array(
		'id' => 9,
		'parent' => 2
	),
	array(
		'id' => 10,
		'parent' => 4
	),
);

$objBuilder = new TreeBuilder();
$objBuilder->setRawData($arrayList);
var_dump($objBuilder->export());

// attempt to retrieve the node with the left value set to 5
var_dump($objBuilder->getNodeWithLeftValue(5)->getId());

// attempt to retrieve the node with the right value set to 7
var_dump($objBuilder->getNodeWithRightValue(7)->getId());

// attempt to retrieve the node with left value set to 0 and right value set to 19
var_dump($objBuilder->getNodeWithLeftAndRightValues(0, 19)->getId());

// attempt to retrieve an inexisting node
var_dump($objBuilder->getNodeWithLeftValue(-1));

// attempt to retrieve the root node
var_dump($objBuilder->getRootNode()->getId());

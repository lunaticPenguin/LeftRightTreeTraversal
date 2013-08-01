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

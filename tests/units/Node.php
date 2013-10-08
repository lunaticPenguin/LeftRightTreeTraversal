<?php

namespace LeftRightTreeTraversal\tests\units;

require_once '../mageekguy.atoum.phar';
include_once '../../LeftRightTreeTraversal/TreeBuilder.php';
include_once '../../LeftRightTreeTraversal/Node.php';

use mageekguy\atoum;
use LeftRightTreeTraversal;

class Node extends atoum\test {
	
	/**
	 * Tests on __construct() method
	 */
	public function test__construct() {
		$objNode = new LeftRightTreeTraversal\Node(1);
		$this->object($objNode)->isInstanceOf('LeftRightTreeTraversal\Node');
	}
	
	/**
	 * Tests on getId() method
	 */
	public function testGetId() {
		$objNode = new LeftRightTreeTraversal\Node(1);
		$this->integer($objNode->getId())->isEqualTo(1);
		$objNode = new LeftRightTreeTraversal\Node(42);
		$this->integer($objNode->getId())->isEqualTo(42);
	}
	
	/**
	 * Tests on getLeftValue() method
	 */
	public function testGetLeftValue() {
		$objNode = new LeftRightTreeTraversal\Node(1);
		$this->integer($objNode->getLeftValue())->isEqualTo(0);
	}
	
	/**
	 * Tests on getRightValue() method
	 */
	public function testGetRightValue() {
		$objNode = new LeftRightTreeTraversal\Node(1);
		$this->integer($objNode->getRightValue())->isEqualTo(0);
	}
	
	/**
	 * Tests on setLeftValue() method
	 */
	public function testSetLeftValue() {
		$objNode = new LeftRightTreeTraversal\Node(1);

		$this->integer($objNode->getLeftValue())->isEqualTo(0);
		$objNode->setLeftValue(42);
		$this->integer($objNode->getLeftValue())->isEqualTo(42);
	}
	
	/**
	 * Tests on setRightValue() method
	 */
	public function testSetRightValue() {
		$objNode = new LeftRightTreeTraversal\Node(1);

		$this->integer($objNode->getRightValue())->isEqualTo(0);
		$objNode->setRightValue(42);
		$this->integer($objNode->getRightValue())->isEqualTo(42);
	}
	
	/**
	 * Tests on hasChildren() method
	 */
	public function testHasChildren() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->boolean($objNode1->hasChildren())->isFalse();
		$objNode1->addChild($objNode2);
		$this->boolean($objNode1->hasChildren())->isTrue();
		
		/* * */
		
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);

		$this->boolean($objNode1->hasChildren())->isFalse();
		$this->boolean($objNode2->hasChildren())->isFalse();
		$objNode1->setParentNode($objNode2);
		$this->boolean($objNode1->hasChildren())->isFalse();
		$this->boolean($objNode2->hasChildren())->isTrue();
	}
	
	/**
	 * Tests on getChildren() method
	 */
	public function testGetChildren() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		$objNode3 = new LeftRightTreeTraversal\Node(3);
		$objNode4 = new LeftRightTreeTraversal\Node(4);
		
		$objNode1->addChild($objNode2);
		$objNode1->addChild($objNode3);
		$objNode3->addChild($objNode4);

		$this->array($objNode1->getChildren())->size->isEqualTo(2);
		$this->array($objNode2->getChildren())->size->isEqualTo(0);
		$this->array($objNode3->getChildren())->size->isEqualTo(1);
		$this->array($objNode4->getChildren())->size->isEqualTo(0);

		$this->boolean($objNode2->isLeaf());
		$this->boolean($objNode4->isLeaf());
	}
	
	/**
	 * Tests on hasChildWithId() method
	 */
	public function testHasChild() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
	
		$this->boolean($objNode1->hasChild($objNode2))->isFalse();
		$objNode1->addChild($objNode2);
		$this->boolean($objNode1->hasChild($objNode2))->isTrue();
	}
	
	/**
	 * Tests on hasChildWithId() method
	 */
	public function testHasChildWithId() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->boolean($objNode1->hasChildWithId(2))->isFalse();
		$objNode1->addChild($objNode2);
		$this->boolean($objNode1->hasChildWithId(2))->isTrue();
	}

	/**
	 * Tests on hasParent() method
	 */
	public function testHasParent() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->boolean($objNode2->hasParent())->isFalse();
		$objNode1->addChild($objNode2);
		$this->boolean($objNode2->hasParent())->isTrue();
	}
	
	/**
	 * Tests on getParent() method
	 */
	public function testGetParent() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->variable($objNode2->getParent())->isNull();
		$objNode1->addChild($objNode2);
		$this->object($objNode2->getParent())->isInstanceOf('LeftRightTreeTraversal\Node');
		
		/* * */
		
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->variable($objNode1->getParent())->isNull();
		$objNode1->setParentNode($objNode2);
		$this->object($objNode1->getParent())->isInstanceOf('LeftRightTreeTraversal\Node');
	}
	
	/**
	 * Tests on isLeaf() method
	 */
	public function testIsLeaf() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);

		$this->boolean($objNode1->isLeaf())->isTrue();
		$this->boolean($objNode2->isLeaf())->isTrue();
		$objNode1->addChild($objNode2);
		$this->boolean($objNode1->isLeaf())->isFalse();
		$this->boolean($objNode2->isLeaf())->isTrue();
		
		/* * */
		
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->boolean($objNode1->isLeaf())->isTrue();
		$this->boolean($objNode2->isLeaf())->isTrue();
		$objNode1->setParentNode($objNode2);
		$this->boolean($objNode1->isLeaf())->isTrue();
		$this->boolean($objNode2->isLeaf())->isFalse();
	}
	
	/**
	 * Tests on setParentNode() method
	 */
	public function testSetParentNode() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->variable($objNode1->getParent())->isNull();
		$this->boolean($objNode2->hasChildren())->isFalse();

		$this->boolean($objNode1->setParentNode($objNode2))->isTrue();
		$this->boolean($objNode1->setParentNode($objNode1))->isFalse(); // cannot set it's parent to itself
		
		$this->object($objNode1->getParent())->isInstanceOf('LeftRightTreeTraversal\Node');
		$this->boolean($objNode2->hasChildren())->isTrue();
	}
	
	/**
	 * Tests on addChild() method
	 */
	public function testAddChild() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->boolean($objNode1->hasChild($objNode2))->isFalse();
		$this->variable($objNode2->getParent())->isNull();
		
		$this->boolean($objNode1->addChild($objNode2))->isTrue();
		$this->boolean($objNode1->addChild($objNode2))->isFalse(); // cannot add it two times
		
		$this->boolean($objNode1->hasChild($objNode2))->isTrue();
		$this->object($objNode2->getParent())->isIdenticalTo($objNode1);
		
		$this->boolean($objNode1->addChild($objNode1))->isFalse(); // // cannot set it's child to itself
	}
	
	/**
	 * Tests on removeChild() method
	 */
	public function testRemoveChild() {
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$this->boolean($objNode1->hasChild($objNode2))->isFalse();
		$this->boolean($objNode1->removeChild($objNode2))->isFalse();
		$this->boolean($objNode1->addChild($objNode2))->isTrue();
		$this->boolean($objNode1->removeChild($objNode2))->isTrue();
		$this->boolean($objNode1->hasChild($objNode2))->isFalse();
	}
	
	/**
	 * Tests on removeChildWithId() method
	 */
	public function testRemoveChildWithId() {
	
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
	
		$this->boolean($objNode1->hasChildWithId(2))->isFalse();
		$this->boolean($objNode1->removeChildWithId(2))->isFalse();
		$this->boolean($objNode1->addChild($objNode2))->isTrue();
		$this->boolean($objNode1->removeChildWithId(2))->isTrue();
		$this->boolean($objNode1->hasChildWithId(2))->isFalse();
	}
}

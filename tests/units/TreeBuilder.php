<?php

namespace LeftRightTreeTraversal\tests\units;

require_once '../mageekguy.atoum.phar';
include_once '../../LeftRightTreeTraversal/TreeBuilder.php';
include_once '../../LeftRightTreeTraversal/Node.php';

use mageekguy\atoum;
use LeftRightTreeTraversal;
use LeftRightTreeTraversal\Node;

class TreeBuilder extends atoum\test {
	
	/**
	 * Tests on __construct() method
	 */
	public function test__construct() {
		
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$this
			->object($objBuilder)
			->isNotNull($objBuilder)
			->isInstanceOf('LeftRightTreeTraversal\TreeBuilder')
		;
		
		/*
		 * Same test, with a custom config
		 */
		$hashConfig = array(
			'key_left' 		=>	'custom_left',
			'key_right'		=>	'custom_right',
			'key_id'		=>	'custom_id',
			'key_parent'	=>	'custom_parent'
		);
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder($hashConfig);
		
		$this
			->object($objBuilder)
			->isNotNull($objBuilder)
			->isInstanceOf('LeftRightTreeTraversal\TreeBuilder')
		;
	}
	
	/**
	 * Tests on addNode() method
	 */
	public function testAddNode() {
		
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		
		$objBuilder->addNode(new LeftRightTreeTraversal\Node(0));
		$arrayResult = $objBuilder->export();
		
		$this
			->array($arrayResult)
				->hasKey(0)
				->size->isEqualTo(1)
			->array($arrayResult[0])
				->hasKey('id')
				->hasKey('left')
				->hasKey('right')
		;
		
		/*
		 * Same test with a custom config
		 */
		
		$hashConfig = array(
				'key_left' 		=>	'custom_left',
				'key_right'		=>	'custom_right',
				'key_id'		=>	'custom_id',
				'key_parent'	=>	'custom_parent'
		);
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder($hashConfig);
		$objBuilder->addNode(new LeftRightTreeTraversal\Node(0));
		
		$arrayResult = $objBuilder->export();
		
		$this
			->object($objBuilder)
			->isNotNull($objBuilder)
			->isInstanceOf('LeftRightTreeTraversal\TreeBuilder')
			
			->array($arrayResult)
				->hasKey(0)
				->size->isEqualTo(1)
			->array($arrayResult[0])
				->hasKey('custom_id')
				->hasKey('custom_left')
				->hasKey('custom_right')
			->integer($arrayResult[0]['custom_id'])
				->isEqualTo(0)
			->integer($arrayResult[0]['custom_left'])
				->isEqualTo(0)
			->integer($arrayResult[0]['custom_right'])
				->isEqualTo(1)
		;
		
	}
	
	/**
	 * Tests on export() method
	 */
	public function testExport() {
		
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$objBuilder->addNode(new LeftRightTreeTraversal\Node(0));
		$arrayResult = $objBuilder->export();
		
		$this
			->array($arrayResult)
			->hasKey(0)
			->size->isEqualTo(1)
			
			->integer($arrayResult[0]['left'])
			->isEqualTo(0)

			->integer($arrayResult[0]['right'])
			->isEqualTo(1)

			->integer($arrayResult[0]['id'])
			->isEqualTo(0)
		;
	}
	
	/**
	 * Tests on setRawData() method
	 */
	public function testSetRawData() {
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$arrayData = array(
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
			)
		);
		$objBuilder->setRawData($arrayData);
		
		$arrayResult = $objBuilder->export();
		$this
			->array($arrayResult)
			->hasKey(0)
			->hasKey(1)
			->hasKey(2)
			->hasKey(3)
			->hasKey(4)
			->hasKey(5)
			->hasKey(6)
			->hasKey(7)
			->hasKey(8)
			->hasKey(9)
			->size
				->isEqualTo(10)
			;
		
		$hashChecks = array(
			2 => array(
				'id' => 2,
			 	'parent' => 1,
				'left'	=> 1,
				'right'	=> 10
			),
			1 => array(
				'id' => 1,
				'parent' => null,
				'left'	=> 0,
				'right'	=> 19
			),
			3 => array(
				'id' => 3,
				'parent' => 2,
				'left'	=> 2,
				'right'	=> 3
			),
			4 => array(
				'id' => 4,
				'parent' => 2,
				'left'	=> 4,
				'right'	=> 7
			),
			5 => array(
				'id' => 5,
				'parent' => 1,
				'left'	=> 11,
				'right'	=> 18
			),
			6 => array(
				'id' => 6,
				'parent' => 5,
				'left'	=> 12,
				'right'	=> 13
			),
			7 => array(
				'id' => 7,
				'parent' => 5,
				'left'	=> 14,
				'right'	=> 15
			),
			8 => array(
				'id' => 8,
				'parent' => 5,
				'left'	=> 16,
				'right'	=> 17
			),
			9 => array(
				'id' => 9,
				'parent' => 2,
				'left'	=> 8,
				'right'	=> 9
			),
			10 => array(
				'id' => 10,
				'parent' => 4,
				'left'	=> 5,
				'right'	=> 6
			)
		);
		
		foreach ($arrayResult as $hashNode) {
			$this
				->array($hashNode)
					->hasKey('id')
					->hasKey('left')
					->hasKey('right')
				->integer($hashNode['id'])
					->isEqualTo($hashChecks[$hashNode['id']]['id'])
				->integer($hashNode['left'])
					->isEqualTo($hashChecks[$hashNode['id']]['left'])
				->integer($hashNode['right'])
					->isEqualTo($hashChecks[$hashNode['id']]['right'])
			;
		}
		
		/*
		 * Check for setRawData() + addNode()
		 **/
		
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$objBuilder->setRawData($arrayData);
		
		$objNode = new LeftRightTreeTraversal\Node(42);
		$objBuilder->addNode($objNode);
		$objBuilder->setParentById(6, 42);
		
		$hashChecks = array(
			2 => array(
				'id' => 2,
				'parent' => 1,
				'left'	=> 1,
				'right'	=> 10
			),
			1 => array(
				'id' => 1,
				'parent' => null,
				'left'	=> 0,
				'right'	=> 21
			),
			3 => array(
				'id' => 3,
				'parent' => 2,
				'left'	=> 2,
				'right'	=> 3
			),
			4 => array(
				'id' => 4,
				'parent' => 2,
				'left'	=> 4,
				'right'	=> 7
			),
			5 => array(
				'id' => 5,
				'parent' => 1,
				'left'	=> 11,
				'right'	=> 20
			),
			6 => array(
				'id' => 6,
				'parent' => 5,
				'left'	=> 12,
				'right'	=> 15
			),
			7 => array(
				'id' => 7,
				'parent' => 5,
				'left'	=> 16,
				'right'	=> 17
			),
			8 => array(
				'id' => 8,
				'parent' => 5,
				'left'	=> 18,
				'right'	=> 19
			),
			9 => array(
				'id' => 9,
				'parent' => 2,
				'left'	=> 8,
				'right'	=> 9
			),
			10 => array(
				'id' => 10,
				'parent' => 4,
				'left'	=> 5,
				'right'	=> 6
			),
			42 => array(
				'id'	=> 42,
				'parent'=> 6,
				'left'	=> 13,
				'right'	=> 14
			)
		);
		
		$arrayResult = $objBuilder->export();
		
		$this
			->array($arrayResult)
			->hasKey(0)
			->hasKey(1)
			->hasKey(2)
			->hasKey(3)
			->hasKey(4)
			->hasKey(5)
			->hasKey(6)
			->hasKey(7)
			->hasKey(8)
			->hasKey(9)
			->hasKey(10)
			->size->isEqualTo(11)
		;
		
		foreach ($arrayResult as $hashNode) {
			$this
				->array($hashNode)
					->hasKey('id')
					->hasKey('left')
					->hasKey('right')
				->integer($hashNode['id'])
					->isEqualTo($hashChecks[$hashNode['id']]['id'])
				->integer($hashNode['left'])
					->isEqualTo($hashChecks[$hashNode['id']]['left'])
				->integer($hashNode['right'])
					->isEqualTo($hashChecks[$hashNode['id']]['right'])
			;
		}
		
		/* **** */

		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$arrayWrongData1 = array(
			array(
				'id' 		=> 1,
				'parent'	=> null
			),
			array(
				'id' 		=> 2,
				'parent'	=> 1
			),
			array(
				'parent'	=> 1
			)
		);
		
		$arrayWrongData2 = array(
			array(
				'id' 		=> 1,
				'parent'	=> 3
			),
			array(
				'id' 		=> 2,
				'parent'	=> 1
			),
			array(
				'id' 		=> 3,
				'parent'	=> 1
			)
		);
		
		// check for malformed input data
		$this
			->exception(
				function() use($objBuilder, $arrayWrongData1) {
					$objBuilder->setRawData($arrayWrongData1);
				}
			)->hasCode(100);
		
		// check for no root node
		$this
			->exception(
				function() use($objBuilder, $arrayWrongData2) {
					$objBuilder->setRawData($arrayWrongData2);
				}
			)->hasCode(110);
	}
	
	/**
	 * Tests on setParentByNodes() method
	 */
	public function testSetParentByNodes() {
		
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$objBuilder->setParentByNodes($objNode1, $objNode2);
		$hashChecks = array(
			1 => array(
				'id' 		=> 1,
				'parent'	=> null,
				'left'		=> 0,
				'right'		=> 3
			),
			2 => array(
				'id' 		=> 2,
				'parent'	=> 1,
				'left'		=> 1,
				'right'		=> 2
			)
		);
		
		$arrayResult = $objBuilder->export();
		$this->array($arrayResult)->size->isEqualTo(2);
		foreach ($arrayResult as $hashNode) {
			$this
				->array($hashNode)
					->hasKey('id')
					->hasKey('left')
					->hasKey('right')
				->integer($hashNode['id'])
					->isEqualTo($hashChecks[$hashNode['id']]['id'])
				->integer($hashNode['left'])
					->isEqualTo($hashChecks[$hashNode['id']]['left'])
				->integer($hashNode['right'])
					->isEqualTo($hashChecks[$hashNode['id']]['right'])
			;
		}
		
		/*
		 * Same tests, inverted
		 */
		
		$objBuilder = new LeftRightTreeTraversal\TreeBuilder();
		$objNode1 = new LeftRightTreeTraversal\Node(1);
		$objNode2 = new LeftRightTreeTraversal\Node(2);
		
		$objBuilder->setParentByNodes($objNode2, $objNode1);
		$hashChecks = array(
			1 => array(
				'id' 		=> 1,
				'parent'	=> 2,
				'left'		=> 1,
				'right'		=> 2
			),
			2 => array(
				'id' 		=> 2,
				'parent'	=> null,
				'left'		=> 0,
				'right'		=> 3
			)
		);
		
		$arrayResult = $objBuilder->export();
		$this->array($arrayResult)->size->isEqualTo(2);
		foreach ($arrayResult as $hashNode) {
			$this
			->array($hashNode)
				->hasKey('id')
				->hasKey('left')
				->hasKey('right')
			->integer($hashNode['id'])
				->isEqualTo($hashChecks[$hashNode['id']]['id'])
			->integer($hashNode['left'])
				->isEqualTo($hashChecks[$hashNode['id']]['left'])
			->integer($hashNode['right'])
				->isEqualTo($hashChecks[$hashNode['id']]['right'])
			;
		}
	}
	
	public function testSetParentByIds() {
		
	}
	
	public function testSetChildByNodes() {
		
	}
	
	public function testSetChildByIds() {
		
	}
	
	public function testGetRootNode() {
		
	}
	
	public function testGetNodeWithLeftAndRightValues() {
		
	}

	public function testGetNodeWithLeftValue() {
	
	}
	
	public function testGetNodeWithRightValue() {
		
	}
}

<?php

class TreeBuilder {

	/**
	 * All the nodes composing the internal graph
	 * @var array array of node
	 */
	protected $arrayNodes;

	/**
	 * Root node of the internal graph
	 * @var Node
	 */
	protected $objRootNode;

	/**
	 * Construct a new tree/graph
	 */
	public function __construct() {
		$this->arrayNodes = array();
	}

	/**
	 * Add node to graph tree
	 * @param Node $objNode
	 */
	public function addNode(Node $objNode) {
		$this->arrayNodes[$objNode->getId()] = $objNode;
	}

	/**
	 * Set the node A parent of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param Node $objNodeA
	 * @param Node $objNodeB
	 */
	public function setParentByNodes(Node $objNodeA, Node $objNodeB) {
		$objNodeB->setParentNode($objNodeA);
	}

	/**
	 * Set the node A child of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param Node $objNodeA
	 * @param Node $objNodeB
	 */
	public function setChildByNodes(Node $objNodeA, Node $objNodeB) {
		$objNodeB->addChild($objNodeA);
	}

	/**
	 * Set the node A child of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param int $intNodeIdA id of node A
	 * @param int $intNodeIdB id of node B
	 */
	public function setChildById($intNodeIdA, $intNodeIdB) {
		if (isset($this->arrayNodes[$intNodeIdA]) && isset($this->arrayNodes[$intNodeIdA])) {
			$this->arrayNodes[$intNodeIdB]->addChild($this->arrayNodes[$intNodeIdA]);
		}
	}

	/**
	 * Set the node A parent of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param int $intNodeIdA id of node A
	 * @param int $intNodeIdB id of node B
	 */
	public function setParentById($intNodeIdA, $intNodeIdB) {
		if (isset($this->arrayNodes[$intNodeIdA]) && isset($this->arrayNodes[$intNodeIdA])) {
			$this->arrayNodes[$intNodeIdB]->setParentNode($this->arrayNodes[$intNodeIdA]);
		}
	}

	/**
	 * Compute the left and right value of for each node which belongs
	 * to internal graph
	 */
	protected function compute() {

		if (!$this->findRoot()) {
			return;
		}

		$objCurrentNode = $this->objRootNode;
		$intCount = 0;
		$this->_computeRecursivePart($objCurrentNode, $intCount);
	}

	protected function _computeRecursivePart(Node $objCurrentNode, &$intCount) {

		$objCurrentNode->setLeftValue($intCount);
		++$intCount;
		if ($objCurrentNode->isLeaf()) {
			$objCurrentNode->setRightValue($intCount);
			++$intCount;
			return;
		}
		$arrayChildren = $objCurrentNode->getChildren();
		foreach ($arrayChildren as $objChildNode) {
			++$intCount;
			$this->_computeRecursivePart($objChildNode, $intCount);
		}
		$objCurrentNode->setRightValue(++$intCount);
	}

	/**
	 * Establish which node is the root node (one of the node has no parent)
	 * @return bool (true if ok, false if failure (no node specified))
	 */
	protected function findRoot() {
		if (empty($this->arrayNodes)) {
			return false;
		}

		$objTmpNode = current($this->arrayNodes);
		$objTmpParentNode = $objTmpNode->getParent();
		while ($objTmpParentNode !== null) {
			$objTmpParentNode = $objTmpParentNode->getParent();
		}
		$this->objRootNode = $objTmpParentNode;

		return true;
	}

	/**
	 * Export internal graph to an array with adequate values
	 * (it depends of the execution context)
	 *
	 * @return array FORMAT : array(array('id'=> #INTEGER, 'left' => #INTEGER, 'right' => #INTEGER))
	 */
	public function export() {

		$this->compute();
		$arrayResult = array();
		foreach ($this->arrayNodes as $objNode) {
			$arrayResult[] = array(
				'id'	=> $objNode->getId(),
				'left'	=> $objNode->getLeftValue(),
				'right'	=> $objNode->getRightValue()
			);
		}
		return $arrayResult;
	}
}

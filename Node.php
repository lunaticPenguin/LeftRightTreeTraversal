<?php

class Node {

	/**
	 * Node's id
	 * @var integer
	 */
	protected $intId;

	/**
	 * Left node's value
	 * It needs the tree computation to be set
	 *
	 * @var int
	 */
	protected $intLeftValue;

	/**
	 * Right node's value
	 * It needs the tree computation to be set
	 *
	 * @var int
	 */
	protected $intRightValue;

	/**
	 *
	 * @var Node
	 */
	protected $objParent;

	/**
	 * Children's nodes
	 *
	 * @var Node's array
	 */
	protected $arrayChildrenNodes;

	/**
	 * Construct a new node and specify id
	 * @param $intId
	 */
	public function __construct($intId) {
		$this->intId = $intId;
	}

	/**
	 * Add child node to the current node.
	 * It automatically add this node as parent of the child node.
	 *
	 * @param Node $objChildNode
	 */
	public function addChild(Node $objChildNode) {
		$objChildNode->setParentNode($this);
		$this->arrayChildrenNodes[$objChildNode->getId()] = $objChildNode;
	}

	/**
	 * Remove child from a node.
	 *
	 * @param Node $objChildToRemove
	 */
	public function removeChild(Node $objChildToRemove) {
		if (isset($this->arrayChildrenNodes[$objChildToRemove->getId()])) {
			unset($this->arrayChildrenNodes[$objChildToRemove->getId()]);
		}
	}

	/**
	 * Set the parent of a node. It automatically add
	 * this node as child of the parent node.
	 *
	 * @param Node $objParent
	 */
	public function setParentNode(Node $objParent) {
		if ($this->objParent !== null) {
			$this->objParent->removeChild($this);
		}
		$this->objParent = $objParent;
	}

	/*
	 * Getters & setters
	 */

	/**
	 * Get node's id
	 * @return int
	 */
	public function getId() {
		return $this->intId;
	}

	/**
	 * Set the left value of the current node
	 * @param int $intLeftValue
	 */
	public function setIntLeftValue($intLeftValue) {
		$this->intLeftValue = $intLeftValue;
	}

	/**
	 * Get the right value of the current node
	 * @return int
	 */
	public function getLeftValue() {
		return $this->intLeftValue;
	}

	/**
	 * Set the right value of the current node
	 * @param int $intRightValue
	 */
	public function setRightValue($intRightValue) {
		$this->intRightValue = $intRightValue;
	}

	/**
	 * Get the right value of the current node
	 * @return int
	 */
	public function getRightValue() {
		return $this->intRightValue;
	}
}

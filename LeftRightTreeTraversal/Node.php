<?php

namespace LeftRightTreeTraversal;

/**
 * Class Node
 * This class define a node which is a vertex in a non-oriented graph.
 * 
 * @author Corentin Legros
 */
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
     * Parent's node of the current node
     *
	 * @var Node
	 */
	protected $objParent;

	/**
	 * Children's nodes
	 *
	 * @var array of nodes
	 */
	protected $arrayChildrenNodes;

	/**
	 * Construct a new node and specify id
	 * @param $intId
	 */
	public function __construct($intId) {
		$this->intId = $intId;
		
		$this->intLeftValue = 0;
		$this->intRightValue = 0;
		
		$this->objParent = null;
		$this->arrayChildrenNodes = array();
	}

	/**
	 * Add child node to the current node.
	 * It automatically add this node as parent of the child node.
	 *
	 * @param Node $objChildNode
	 * @return boolean
	 */
	public function addChild(Node $objChildNode) {
		
		if (!$this->hasChild($objChildNode) && $this !== $objChildNode) {
			$this->arrayChildrenNodes[$objChildNode->getId()] = $objChildNode;
			if ($objChildNode->getParent() !== $this) {
				$objChildNode->setParentNode($this);
			}
			return true;
		}
		return false;
	}

	/**
	 * Attempt to remove a child from the current node.
	 *
	 * @param Node $objChildToRemove
	 * @return boolean
	 */
	public function removeChild(Node $objChildToRemove) {
		return $this->removeChildWithId($objChildToRemove->getId());
	}
	
	/**
	 * Attempt to remove a child with given id from the current node.
	 * @param integer $intNodeId
	 * @return boolean
	 */
	public function removeChildWithId($intNodeId) {
		if ($this->hasChildWithId($intNodeId)) {
			unset($this->arrayChildrenNodes[$intNodeId]);
			return true;
		}
		return false;
	}

	/**
	 * Set the parent of a node. It automatically add
	 * this node as child of the parent node.
	 *
	 * @param Node $objParent
	 * @return boolean
	 */
	public function setParentNode(Node $objParent) {
		
		if ($this === $objParent || $this->objParent === $objParent) {
			return false;
		}
		
		if ($this->objParent !== null) {
			$this->objParent->removeChild($this);
			$this->objParent = null;
		}
		
		$objParent->addChild($this);
		$this->objParent = $objParent;
		return true;
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
	public function setLeftValue($intLeftValue) {
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

	/**
	 * Return the parent node
	 * @return \Node
	 */
	public function getParent() {
		return $this->objParent;
	}

	/**
	 * Allow to know if a node is a leaf (<=> has no children)
	 * @return bool
	 */
	public function isLeaf() {
		return empty($this->arrayChildrenNodes);
	}

	/**
	 * Return all the children of a node
	 * @return array
	 */
	public function getChildren() {
		return $this->arrayChildrenNodes;
	}
	
	/**
	 * Allow to know if the current node has parent
	 * @return boolean
	 */
	public function hasParent() {
		return $this->objParent !== null;
	}
	
	/**
	 * Allow to know if the current node has children
	 * @return boolean
	 */
	public function hasChildren() {
		return !empty($this->arrayChildrenNodes);
	}

	/**
	 * Allow to know if a node is a child of the current node
	 * @param integer $intNodeId
	 * @return boolean
	 */
	public function hasChild(Node $objNode) {
		return $this->hasChildWithId($objNode->getId());
	}
	
	/**
	 * Allow to know if a node with given id is a child of the current node
	 * @param integer $intNodeId
	 * @return boolean
	 */
	public function hasChildWithId($intNodeId) {
		return array_key_exists($intNodeId, $this->arrayChildrenNodes);
	}
}

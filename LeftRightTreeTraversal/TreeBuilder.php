<?php

namespace LeftRightTreeTraversal;

/**
 * Class TreeBuilder
 * This class define the left-right tree builder
 * which is responsible for building the graph with correct left-right values
 *
 * @author Corentin Legros
 */
class TreeBuilder {

	/**
	 * All the nodes composing the internal graph
	 * @var array of node
	 */
	protected $arrayNodes;

	/**
	 * Allow to force the data check if using the setRawData method
	 * @var bool
	 */
	protected $boolForcedPostCheckProcess;

    /**
     * Hash of used config. The content can be overridden but keep in mind that
     * some checks are processed in order to keep a consistent process.
     *
     * @var array
     * @see TreeBuilder::__construct()
     */
    protected $hashConfig;

	/**
	 * Construct a new tree/graph
	 */
	public function __construct(array $hashConfig = array()) {

        $hashConfigDefaultValues = array(
            'key_left'      => 'left',
            'key_right'     => 'right',
            'key_id'        => 'id',
            'key_parent'    => 'parent'
        );

        $this->hashConfig = array_merge($hashConfigDefaultValues, $hashConfig);
        $arrayAvailableConfig = array_keys($hashConfigDefaultValues);
        foreach ($arrayAvailableConfig as $strKey1Name) {
            foreach ($arrayAvailableConfig as $strKey2Name) {
                if ($strKey1Name !== $strKey2Name) {
                    if ($this->hashConfig[$strKey1Name] === $this->hashConfig[$strKey2Name]) {
                        $this->hashConfig[$strKey1Name] = $hashConfigDefaultValues[$strKey1Name];
                        $this->hashConfig[$strKey2Name] = $hashConfigDefaultValues[$strKey2Name];
                    }
                }
            }
        }
        
		$this->arrayNodes = array();
		$this->boolForcedPostCheckProcess = false;
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
		if (array_key_exists($intNodeIdA, $this->arrayNodes) && array_key_exists($intNodeIdB, $this->arrayNodes)) {
			$this->arrayNodes[$intNodeIdB]->addChild($this->arrayNodes[$intNodeIdA]);
		} else {
			$this->boolForcedPostCheckProcess = true;
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
		if (array_key_exists($intNodeIdA, $this->arrayNodes) && array_key_exists($intNodeIdB, $this->arrayNodes)) {
			$this->arrayNodes[$intNodeIdB]->setParentNode($this->arrayNodes[$intNodeIdA]);
		} else {
			$this->boolForcedPostCheckProcess = true;
		}
	}

	/**
	 * Compute the left and right value of for each node which belongs
	 * to internal graph
	 *
	 * @return array FORMAT : array(array('id'=> #INTEGER, 'left' => #INTEGER, 'right' => #INTEGER))
	 */
	public function export() {

		if (empty($this->arrayNodes)) {
			return array();
		}

		$objRootNode = current($this->arrayNodes);
		while ($objRootNode->getParent() !== null) {
			$objRootNode = $objRootNode->getParent();
		}

		$intCount = 0;
		$this->_computeRecursivePart($objRootNode, $intCount);

		$arrayResult = array();
		foreach ($this->arrayNodes as $objNode) {
			$arrayResult[] = array(
                $this->hashConfig['key_id']     => $objNode->getId(),
                $this->hashConfig['key_left']   => $objNode->getLeftValue(),
                $this->hashConfig['key_right']  => $objNode->getRightValue()
			);
		}
		return $arrayResult;
	}

	/**
	 * Compute the graph thanks to a recursive behaviour
	 *
	 * @param Node $objCurrentNode
	 * @param $intCount
	 */
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
			$this->_computeRecursivePart($objChildNode, $intCount);
		}
		$objCurrentNode->setRightValue($intCount);
		++$intCount;
	}

	/**
	 * Allow users to build the graph by directly passing an array
	 * which defines all relations parent/child
	 * The data format MUST be the following :
	 * ARRAY(ARRAY('id' => #INTEGER, 'parent' => (#INTEGER|null))
	 *
	 * /!\ No cyclic graphs supported.
	 * If there isn't any root node the process will be aborted.
	 *
	 * @param array $arrayData raw data with specified structure format
	 * @param bool $boolCheckRelations if nodes's relations have to be checked by a post-process.\
	 *    	Set to false improve the process, but be sure to provide data rightly sorted.\
	 * 		Otherwise the check is forced.
	 */
	public function setRawData(array $arrayData, $boolCheckRelations = true) {
		$boolHasRootNode = false;
		foreach ($arrayData as $hashNodeData) {
			if (!array_key_exists($this->hashConfig['key_id'], $hashNodeData)
                || !array_key_exists($this->hashConfig['key_parent'], $hashNodeData)) {
				exit('malformed input raw data');
			}

			$boolHasRootNode = !$boolHasRootNode ? is_null($hashNodeData[$this->hashConfig['key_parent']]) : true;

			$this->addNode(new Node($hashNodeData[$this->hashConfig['key_id']]));
			if (!$boolCheckRelations && $hashNodeData[$this->hashConfig['key_parent']] !== null) {
				$this->setParentById($hashNodeData[$this->hashConfig['key_parent']], $hashNodeData[$this->hashConfig['key_id']]);
			}
		}

		if (!$boolHasRootNode) {
			exit('no root node found');
		}

		if ($boolCheckRelations || $this->boolForcedPostCheckProcess) {
			foreach ($arrayData as $hashNodeData) {
				$this->setParentById($hashNodeData[$this->hashConfig['key_parent']], $hashNodeData[$this->hashConfig['key_id']]);
			}
		}
	}
	
	/**
	 * Allow to found a node by it's left value
	 * 
	 * @param integer $intLeftValue
	 * @return Node|NULL
	 */
	public function getNodeWithLeftValue($intLeftValue) {
		return $this->_getNodeWithValue($intLeftValue, null);
	}
	
	/**
	 * Allow to found a node by it's right value
	 *
	 * @param integer $intRightValue
	 * @return Node|NULL
	 */
	public function getNodeWithRightValue($intRightValue) {
		return $this->_getNodeWithValue(null, $intRightValue);
	}
	
	/**
	 * Allow to found a node by it's left and right values
	 *
	 * @param integer $intLeftValue
	 * @param integer $intRightValue
	 * 
	 * @return Node|NULL
	 */
	public function getNodeWithLeftAndRightValues($intLeftValue, $intRightValue) {
		return $this->_getNodeWithValue($intLeftValue, $intRightValue);
	}
	
	/**
	 * Allow to retrieve a specific node with left and/or right value(s)
	 * 
	 * @param integer|null $intLeftValue
	 * @param integer|null $intRightValue
	 * 
	 * @return Node|NULL if not found
	 */
	protected function _getNodeWithValue($intLeftValue, $intRightValue) {
		
		if ($intLeftValue !== null && $intRightValue === null) {
			foreach ($this->arrayNodes as $objNode) {
				if ($objNode->getLeftValue() === $intLeftValue) {
					return $objNode;
				}
			}
		} else if ($intLeftValue === null && $intRightValue !== null) {
			foreach ($this->arrayNodes as $objNode) {
				if ($objNode->getRightValue() === $intRightValue) {
					return $objNode;
				}
			}
		} else if ($intLeftValue !== null && $intRightValue !== null) {
			foreach ($this->arrayNodes as $objNode) {
				if ($objNode->getLeftValue() === $intLeftValue && $objNode->getRightValue() === $intRightValue) {
					return $objNode;
				}
			}
		} else {
			return null;
		}
	}
	
	/**
	 * Allow to retrieve the root node
	 * 
	 * @return Node|NULL
	 */
	public function getRootNode() {
		foreach ($this->arrayNodes as $objNode) {
			if ($objNode->getParent() === null) {
				return $objNode;
			}
		}
		return null;
	}
}

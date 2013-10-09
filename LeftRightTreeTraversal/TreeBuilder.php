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
     * Order of the graph
     * @var integer
     */
    protected $intOrder;
    
    /**
     * Flag to know if export has been computed
     * @var boolean
     */
    protected $boolIsComputed;

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
		$this->boolIsComputed = false;
		$this->intOrder = 0;
	}

	/**
	 * Add node to graph tree
	 * @param Node $objNode
	 * @return boolean
	 */
	public function addNode(Node $objNode) {
		if (!$this->hasNode($objNode)) {
			$this->arrayNodes[$objNode->getId()] = $objNode;
			++$this->intOrder;
			return true;
		}
		return false;
	}
	
	/**
	 * Set the node A parent of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param Node $objNodeA
	 * @param Node $objNodeB
	 * 
	 * @return boolean
	 */
	public function setParentByNodes(Node $objNodeA, Node $objNodeB) {
		
		// two checks to verify if the nodes belongs to the builder
		if (!$this->hasNode($objNodeA)) {
			$this->addNode($objNodeA);
		}
		
		if (!$this->hasNode($objNodeB)) {
			$this->addNode($objNodeB);
		}
		
		return $objNodeB->setParentNode($objNodeA);
	}

	/**
	 * Set the node A child of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param Node $objNodeA
	 * @param Node $objNodeB
	 * 
	 * @return boolean
	 */
	public function setChildByNodes(Node $objNodeA, Node $objNodeB) {
		
		// two checks to verify if the nodes belongs to the builder
		if (!$this->hasNode($objNodeA)) {
			$this->addNode($objNodeA);
		}
		
		if (!$this->hasNode($objNodeB)) {
			$this->addNode($objNodeB);
		}
		
		return $objNodeB->addChild($objNodeA);
	}

	/**
	 * Set the node A child of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param int $intNodeIdA id of node A
	 * @param int $intNodeIdB id of node B
	 * 
	 * @return boolean
	 */
	public function setChildById($intNodeIdA, $intNodeIdB) {
		
		if ($this->hasNodeWithId($intNodeIdA) && $this->hasNodeWithId($intNodeIdB)) {
			
			return $this->arrayNodes[$intNodeIdB]->addChild($this->arrayNodes[$intNodeIdA]);
		}
		
		// force checks of relations if settlement is done using self::setRawData() method
		$this->boolForcedPostCheckProcess = true;
		return false;
	}

	/**
	 * Set the node A parent of node B.
	 * This method only delegates instructions to the Node class's methods.
	 *
	 * @param int $intNodeIdA id of node A
	 * @param int $intNodeIdB id of node B
	 * 
	 * @return boolean
	 */
	public function setParentById($intNodeIdA, $intNodeIdB) {
		
		if ($this->hasNodeWithId($intNodeIdA) && $this->hasNodeWithId($intNodeIdB)) {
			return $this->arrayNodes[$intNodeIdB]->setParentNode($this->arrayNodes[$intNodeIdA]);
		}
		
		// force checks of relations if settlemet is done using self::setRawData() method
		$this->boolForcedPostCheckProcess = true;
		return false;
	}

	/**
	 * Compute the left and right value for each node which belongs
	 * to internal graph.
	 * 
	 * @return \LeftRightTreeTraversal\TreeBuilder
	 */
	public function compute() {

		if (empty($this->arrayNodes)) {
			return $this;
		}

		$objRootNode = $this->getRootNode();
		if (is_null($objRootNode)) {
			return $this;
		}

		$intCount = 0;
		$this->_computeRecursivePart($objRootNode, $intCount);
		$this->boolIsComputed = true;
		return $this;
	}
	
	/**
	 * Export the whole computed graph as an array of arrays. If the graph hasn't been computed, an empty
	 * array will be returned.
	 * 
	 * @return array FORMAT : array(array('id'=> #INTEGER, 'left' => #INTEGER, 'right' => #INTEGER))
	 */
	public function export() {
		
		if (!$this->boolIsComputed) {
			return array();
		}
		
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
				throw new \InvalidArgumentException(
					sprintf('Malformed input raw data ([%s] and [%s] keys required).',
						$this->hashConfig['key_id'],
						$this->hashConfig['key_parent']),
					100
				);
			}

			$boolHasRootNode = !$boolHasRootNode ? is_null($hashNodeData[$this->hashConfig['key_parent']]) : true;

			$this->addNode(new Node($hashNodeData[$this->hashConfig['key_id']]));
			if (!$boolCheckRelations && $hashNodeData[$this->hashConfig['key_parent']] !== null) {
				$this->setParentById($hashNodeData[$this->hashConfig['key_parent']], $hashNodeData[$this->hashConfig['key_id']]);
			}
		}

		if (!$boolHasRootNode) {
			throw new \InvalidArgumentException('Root node cannot be found.', 110);
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
	
	/**
	 * Allow to know if the builder already has a node
	 * @param Node $objNode
	 */
	public function hasNode(Node $objNode) {
		return $this->hasNodeWithId($objNode->getId());
	}
	
	/**
	 * Allow to know if the builder already has a node with a specific id
	 * @param integer $intNodeId
	 */
	public function hasNodeWithId($intNodeId) {
		return array_key_exists($intNodeId, $this->arrayNodes);
	}
	
	/**
	 * Allow to get the graph's order
	 * @return integer
	 */
	public function getOrder() {
		return $this->intOrder;
	}
	
	/**
	 * Allow to know if the process has been computed
	 * @return boolean
	 */
	public function isComputed() {
		return $this->boolIsComputed;
	}
}

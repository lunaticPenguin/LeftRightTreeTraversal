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
	 * @var array array of node
	 */
	protected $arrayNodes;

	/**
	 * Allow to force the data check if using the setRawData method
	 * @var bool
	 */
	protected $boolForcedPostCheckProcess;

    /**
     * @var array
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
		if (isset($this->arrayNodes[$intNodeIdA]) && isset($this->arrayNodes[$intNodeIdA])) {
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
		if (isset($this->arrayNodes[$intNodeIdA]) && isset($this->arrayNodes[$intNodeIdA])) {
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
}

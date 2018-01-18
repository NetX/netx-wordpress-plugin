<?php
/**
 * netxCategoryProc class file
 *
 * Contains definition for the netxCategoryProc class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Implements category related API calls
 *
 * @package NetxRestAPI
 */
class netxCategoryProc extends netxRestClient {
	/**
	 * Constructor
	 *
	 * @param netxConnection $conn current connection to server
	 * @return netxCategoryProc
	 */
	public function __construct(netxConnection $conn) {
		parent::__construct('category', $conn);
	}

	/**
	 * Gets associative array of Category objects
	 *
	 * @return array associative array tree of all categories
	 */
	public function getCategoryTree() {
		$xml = $this->doCommand('/0');
		$catTree = netxBeanFactory::parseCategoryTree($xml);
		return $catTree;
	}

	/**
	 * Returns array of the top level categories
	 *
	 * @return array array of netxCategory objects
	 */
	public function getTopLevelCategories() {
		$catList = array();
		$xml = $this->doCommand('/1');
		$catList = netxBeanFactory::parseCategoryXML($xml);
		return $catList;
	}

	/**
	 * Gets Category by category ID
	 *
	 * @param int $catID id of category to retrieve
	 * @return array array of netxCategory objects
	 */
	public function getCategory($catID) {
		$catList = array();
		$cmdString = '/' . $catID;
		$xml = $this->doCommand($cmdString);
		$catList = netxBeanFactory::parseCategoryXML($xml);
		return $catList;
	}

	/**
	 * Create a new category
	 *
	 * Create a new category by passing the id of the parent of the new category.
	 *
	 * @param int $parentID id of category to create new category under
	 * @param string $catName name of new category
	 * @return netxCategory new category
	 */
	public function createCategory($parentID, $newCategoryName) {
		$catList = array();
		$parentPath = $this->getPath($parentID);
		$cmdString = '/create' . $parentPath . $newCategoryName;
		$xml = $this->doCommand($cmdString);
		$catList = netxBeanFactory::parseCategoryXML($xml);
		return $catList;
	}

	/**
	 * Get the path to a category
	 *
	 * Return the path of the category specified by category ID
	 *
	 * @param int $catID id of category to return path of
	 * @return string path of category
	 */
	private function getPath($catID) {
		if ($catID == 1) {
			return '/';
		} else {
			$childList = $this->getCategory($catID);
			$firstChildRepoDir = $childList[0]->getRepositoryDirectory();
			$basePath = '/' . substr($firstChildRepoDir, 0, strrpos($firstChildRepoDir, '/')) . '/';
			return $basePath;
		}
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxCategoryProc Object                         |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}

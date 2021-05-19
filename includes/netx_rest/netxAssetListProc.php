<?php
/**
 * netxAssetListProc class file
 *
 * Contains definition for the netxAssetListProc class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Implements asset list related API calls
 *
 * @package NetxRestAPI
 */
class netxAssetListProc extends netxRestClient {
	/**
	 * Constructor
	 *
	 * @param netxConnection $conn current connection to server
	 * @return netxAssetProc
	 */
	public function __construct(netxConnection $conn) {
		parent::__construct('list', $conn);
	}

	/**
	 * Build portion of REST URL for paging
	 *
	 * @param int $pageNum page number, or 0 for no paging
	 * @return string portion of REST URL for paging
	 */
	private function pageString($pageNum = 0) {
		if ($pageNum > 0) {
			return '/page/' . $pageNum;
		} else {
			return '';
		}
	}

	/**
	 * Get list of assets by category ID
	 *
	 * @param int $catID id of category to list assets from
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
    public function getAssetsByCategoryID($catID, $pageNum = 0) {
		$assetList = array();
		$restCmd = '/category/id/' . $catID . $this->pageString($pageNum);
        $xml = $this->doCommand($restCmd);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Get list of assets by category path
	 *
	 * @param string $catPath path of category to list assets from
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
	public function getAssetsByCategoryPath($catPath, $pageNum = 0) {
		$assetList = array();
        $restCmd = '/category/path/' . $catPath . $this->pageString($pageNum);
		$xml = $this->doCommand($restCmd);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Get list of assets by keyword
	 *
	 * @param string $keyword keyword to return assets for
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
	public function getAssetsByKeyword($keyword, $pageNum = 0) {
		$assetList = array();
		$restCmd = '/keyword/' . $keyword . $this->pageString($pageNum);
		$xml = $this->doCommand($restCmd);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Get list of assets by metadata
	 *
	 * @param string $attributeName name of attribute to use for search
	 * @param string $attributeValue value of attribute to use for search
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
	public function getAssetsByMetadata($attributeName, $attributeValue, $pageNum = 0) {
		$assetList = array();
		$restCmd = '/metadata/' . $attributeName . '/' . $attributeValue . $this->pageString($pageNum);
		$xml = $this->doCommand($restCmd);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxAssetListProc Object                        |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}


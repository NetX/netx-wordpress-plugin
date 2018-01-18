<?php
/**
 * netxAssetProc class file
 *
 * Contains definition for the netxAssetProc class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Main include file for NetX REST library
 */
require_once('netx_rest_api.inc.php');

/**
 * Implements asset related API calls
 *
 * @package NetxRestAPI
 */
class netxAssetProc extends netxRestClient {
	/**
	 * Constructor
	 *
	 * @param netxConnection $conn current connection to server
	 * @return netxAssetProc
	 */
	public function __construct(netxConnection $conn) {
		parent::__construct('asset', $conn);
	}

	/**
	 * Add asset to category
	 *
	 * @param int $catID id of category to add asset to
	 * @param int $assetID id of asset to add to  category
	 * @return netxAsset asset that was updated
	 */
	public function addToCategory($assetID, $catID) {
		$cmdString = '/' . $assetID . '/addCategory/' . $catID;
		$xml = $this->doCommand($cmdString);
		$asset = netxBeanFactory::parseBeanXML($xml);
		return $asset;
	}

	/**
	 * Update asset attribute
	 *
	 * @param int $assetID id of asset to update
	 * @param string $attribute name of attribute
	 * @param string $newVal new value for attribute
	 * @return netxAsset asset that was updated
	 */
	public function updateAttribute($assetID, $attribute, $newVal) {
		$cmdString = '/' . $assetID . '/attribute/' . $attribute . '/' . $newVal;
		$xml = $this->doCommand($cmdString);
		$asset = netxBeanFactory::parseBeanXML($xml);
		return $asset;
	}

	/**
	 * Get an asset
	 *
	 * @param int $assetID id of asset to retrieve
	 * @return netxAsset retrieved asset
	 */
	public function getAsset($assetID) {
		$cmdString = '/' . $assetID;
		$xml = $this->doCommand($cmdString);
		$asset = netxBeanFactory::parseBeanXML($xml);
		return $asset;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxAssetProc Object                            |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}


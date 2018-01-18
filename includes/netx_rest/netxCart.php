<?php
/**
 * netxCart class file
 *
 * Contains definition for the netxCart class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Implements Cart functionality
 *
 * @package NetxRestAPI
 */
class netxCart extends netxRestClient {
	/**
	 * Constructor
	 *
	 * @return netxCart
	 */
	public function __construct(netxConnection $conn) {
		parent::__construct('cart', $conn);
	}

	/**
	 * Add an asset to cart by asset ID
	 *
	 * @param int $assetID ID of asset to add to cart
	 * @return array contents of cart
	 */
	public function addAssetByID($assetID) {
		$assetList = array();
		$cmdString = '/add/' . $assetID;
		$xml = $this->doCommand($cmdString);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * List carts contents
	 *
	 * @return array contents of cart
	 */
	public function listContents() {
		$assetList = array();
		$xml = $this->doCommand();
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Add an asset to a cart
	 *
	 * @param netxAsset $a asset to add to cart
	 * @return array contents of cart
	 */
	public function addAsset(netxAsset $asset) {
		$assetID = $asset->getAssetID();
		return $this->addAssetByID($assetID);
	}

	/**
	 * Remove all contents from cart
	 *
	 * @return array contents of cart
	 */
	public function clear() {
		$assetList = array();
		$cmdString = '/clear';
		$xml = $this->doCommand($cmdString);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Email cart to an email address
	 *
	 * @param string $emailAddress email address
	 * @return array contents of cart
	 */
	public function emailTo($emailAddress) {
		$assetList = array();
		$cmdString = '/email/' . $emailAddress;
		$xml = $this->doCommand($cmdString);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Remove an asset from the cart by asset ID
	 *
	 * @param netxAsset $assetID asset to be removed from cart
	 * @return array contents of cart
	 */
	public function removeAssetByID($assetID) {
		$assetList = array();
		$cmdString = '/remove/' . $assetID;
		$xml = $this->doCommand($cmdString);
		$assetList = netxBeanFactory::parseAssetListXML($xml);
		return $assetList;
	}

	/**
	 * Remove an asset from the cart
	 *
	 * @param netxAsset $a asset to be removed from cart
	 * @return array contents of cart
	 */
	public function removeAsset($asset) {
		$assetID = $asset->getAssetID();
		return $this->removeAssetByID($assetID);
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxCart Object                                 |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}

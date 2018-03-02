<?php
/**
 * netxNetX class file
 *
 * Contains definition for the netxNetX class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Primary interface class for NetX REST API
 *
 * @package NetxRestAPI
 */
class netxNetX {
	/**
	 * Current connection
	 *
	 * @var netxUser current connection
	 * @access private
	 */
	private $connection = null;

	/**
	 * Constructor
	 *
	 * Logs in and creates a connection. There are two options for logging in, either the REST
	 * /login command, or via HTTP Basic Auth. Use HTTP Basic Auth b setting the $useHttpBasicAuth
	 * parameter to true.
	 *
	 * Currently, a User object is not created if you use HTTP Basic Auth, so if you need
	 * to get a User object, you need to use the REST /login method of authenticating.
	 *
	 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
	 * @param string $username Netx username
	 * @param string $password password
	 * @param string $baseURI base REST URI (i.e. 'poached.netx.net')
	 * @param boolean $useHttpBasicAuth true to use HTTP basic auth, false to use the REST login command to log in
	 * @return netxNetX
	 */
	public function __construct($username, $password, $serverURL, $useHttpBasicAuth = false, $useHttps = true) {
		if ($useHttpBasicAuth) {
			$this->connection = netxConnection::ConnectBasicAuth($username, $password, $serverURL, $useHttps);
		} else {
			$this->connection = netxConnection::ConnectLogin($username, $password, $serverURL, $useHttps);
		}
	}

	/**
	 * Gets associative array of Category objects
	 *
	 * @return array
	 */
	public function getCategoryTree() {
		$categoryProc = new netxCategoryProc($this->connection);
		$categoryTree = $categoryProc->getCategoryTree();
		return $categoryTree;
	}

	/**
	 * Gets flat array of categories
	 *
	 * The array key is the full category path, and the value is the
	 * category id. The keys are sorted in ascending alphabetical
	 * order.
	 *
	 * @return array categories
	 */
	public function getCategoryPathList() {
		$tree = $this->getCategoryTree();
		$list = $this->buildCategoryPathList($tree[1]['children'], '');
		return $list;
	}

	/**
	 * Builds flat category array based on category tree array
	 *
	 * @param array $tree category tree
	 * @param string $prefix category path of parent
	 * @param array $list flat list of categories being built
	 * @return array categories
	 */
	private function buildCategoryPathList($tree, $prefix, $list = array()) {
		foreach ($tree as $idx => $cat) {
			$cat = array_pop($tree);
			$catPath = $prefix . '/' . $cat['label'];
			$list[$catPath] = $cat['categoryId'];
			if (count($cat['children']) > 0) {
				$list = $this->buildCategoryPathList($cat['children'], $catPath, $list);
			}
		}
		ksort($list);
		return $list;
	}

	/**
	 * Returns array of the top level categories
	 *
	 * @return array array of netxCategory objects
	 */
	public function getTopLevelCategories() {
		$categoryProc = new netxCategoryProc($this->connection);
		$categoryList = $categoryProc->getTopLevelCategories();
		return $categoryList;
	}

	/**
	 * Gets Category by category ID
	 *
	 * @param int $id id of category to retrieve
	 * @return array array of netxCategory objects
	 */
	public function getCategory($catID) {
		$categoryProc = new netxCategoryProc($this->connection);
		$category = $categoryProc->getCategory($catID);
		return $category;
	}

	/**
	 * Create a new category
	 *
	 * Create a new category by passing the id of the parent of the new category. To
	 * create a new top level category, use $parentID = 1. Or you can use
	 * netxNetx::createTopLevelCategory(), which just does the same thing.
	 *
	 * @param int $parentID id of category to create new category under
	 * @param string $catName name of new category
	 * @return array array of netxCategory objects
	 */
	public function createCategory($parentID, $catName) {
		$categoryProc = new netxCategoryProc($this->connection);
		$categoryList = $categoryProc->createCategory($parentID, $catName);
		return $categoryList;
	}

	/**
	 * Create a new top level category
	 *
	 * @param string $catName name of new category
	 * @return array array of netxCategory objects
	 */
	public function createTopLevelCategory($catName) {
		return $this->createCategory(1, $catName);
	}

	/**
	 * Get list of assets by category ID
	 *
	 * @param int $catID id of category to list assets from
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
	public function getAssetsByCategoryID($catID, $pageNum = 0) {
		$assetListProc = new netxAssetListProc($this->connection);
		$assetList = $assetListProc->getAssetsByCategoryID($catID, $pageNum);
		return $assetList;
	}

	/**
	 * Get number of assets by category ID
	 *
	 * @param int $catID id of category to list assets from
	 * @return int number of assets in category
	 */
	public function getAssetCountByCategoryID($catID) {
		$assetList = $this->getAssetsByCategoryID($catID, 0);
		return count($assetList);
	}

	/**
	 * Get list of assets by category path
	 *
	 * @param string $catPath path of category to list assets from
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
	public function getAssetsByCategoryPath($catPath, $pageNum = 0) {
		$assetListProc = new netxAssetListProc($this->connection);
		$assetList = $assetListProc->getAssetsByCategoryPath($catPath, $pageNum);
		return $assetList;
	}

	/**
	 * Get number of assets by category path
	 *
	 * @param string $catPath path of category to list assets from
	 * @return int number of assets in category
	 */
	public function getAssetCountByCategoryPath($catPath) {
		$assetList = $this->getAssetsByCategoryPath($catPath, 0);
		return count($assetList);
	}

	/**
	 * Get list of assets by keyword
	 *
	 * @param string $keyword keyword to return assets for
	 * @param int $pageNum page number, or 0 for no paging
	 * @return array array of netxAssetList objects
	 */
	public function getAssetsByKeyword($keyword, $pageNum = 0) {
		$assetListProc = new netxAssetListProc($this->connection);
		$assetList = $assetListProc->getAssetsByKeyword($keyword, $pageNum);
		return $assetList;
	}

	/**
	 * Get number of assets by keyword
	 *
	 * @param string $keyword keyword to return assets for
	 * @return int number of assets by keyword
	 */
	public function getAssetCountByKeyword($keyword) {
		$assetList = $this->getAssetsByKeyword($keyword, 0);
		return count($assetList);
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
		$assetListProc = new netxAssetListProc($this->connection);
		$assetList = $assetListProc->getAssetsByMetadata($attributeName, $attributeValue, $pageNum);
		return $assetList;
	}

	/**
	 * Get number of assets by metadata
	 *
	 * @param string $attributeName name of attribute to use for search
	 * @param string $attributeValue value of attribute to use for search
	 * @return int number of assets by metadata
	 */
	public function getAssetCountByMetadata($attributeName, $attributeValue) {
		$assetList = $this->getAssetsByMetadata($attributeName, $attributeValue, 0);
		return count($assetList);
	}

	/**
	 * Update asset attribute
	 *
	 * @param int $assetID id of asset to update
	 * @param string $attribute name of attribute
	 * @param string $newVal new value for attribute
	 * @return netxAsset asset that was updated
	 */
	public function updateAssetAttribute($assetID, $attribute, $newVal) {
		$assetProc = new netxAssetProc($this->connection);
		$asset = $assetProc->updateAttribute($assetID, $attribute, $newVal);
		return $asset;
	}

	/**
	 * Add asset to category
	 *
	 * @param int $catID id of category to add asset to
	 * @param int $assetID id of asset to add to  category
	 * @return netxAsset asset that was updated
	 */
	public function addAssetToCategory($assetID, $catID) {
		$assetProc = new netxAssetProc($this->connection);
		$asset = $assetProc->addToCategory($assetID, $catID);
		return $asset;
	}

	/**
	 * Get an asset
	 *
	 * @param int $assetID id of asset to retrieve
	 * @return netxAsset retrieved asset
	 */
	public function getAsset($assetID) {
		$assetProc = new netxAssetProc($this->connection);
		$asset = $assetProc->getAsset($assetID);
		return $asset;
	}

	/**
	 * List carts contents
	 *
	 * @return array contents of cart
	 */
	public function listCartContents() {
		$cart = new netxCart($this->connection);
		return $cart->listContents();
	}

	/**
	 * Add an asset to a cart by asset ID
	 *
	 * @param int $assetID ID of asset to add to cart
	 * @return array contents of cart
	 */
	public function addAssetToCartByID($assetID) {
		$cart = new netxCart($this->connection);
		return $cart->addAssetByID($assetID);
	}

	/**
	 * Add an asset to a cart
	 *
	 * @param netxAsset $asset asset to add to cart
	 * @return array contents of cart
	 */
	public function addAssetToCart(netxAsset $asset) {
		$cart = new netxCart($this->connection);
		return $cart->addAsset($asset);
	}

	/**
	 * Remove all contents from cart
	 *
	 * @return array contents of cart
	 */
	public function clearCart() {
		$cart = new netxCart($this->connection);
		return $cart->clear();
	}

	/**
	 * Email cart to an email address
	 *
	 * @param string $emailAddress email address
	 * @return array contents of cart
	 */
	public function emailCartTo($emailAddress) {
		$cart = new netxCart($this->connection);
		return $cart->emailTo($emailAddress);
	}

	/**
	 * Remove an asset from the cart by asset ID
	 *
	 * @param netxAsset $assetID asset to be removed from cart
	 * @return array contents of cart
	 */
	public function removeAssetFromCartByID($assetID) {
		$cart = new netxCart($this->connection);
		return $cart->removeAssetByID($assetID);
	}

	/**
	 * Remove an asset from the cart
	 *
	 * @param netxAsset $asset asset to be removed from cart
	 * @return array contents of cart
	 */
	public function removeAssetFromCart($asset) {
		$cart = new netxCart($this->connection);
		return $cart->removeAsset($asset);
	}

	/**
	 * Import a file into NetX
	 *
	 * @param string $localFilePath path of local file to import into NetX
	 * @param string $netxCategoryPath NetX category path to import to
	 * @return netxAsset asset object, or false if imort failed
	 */
	public function fileImport($localFilePath, $netxCategoryPath) {
		$importer = new netxImporter($this->connection);
		return $importer->import($localFilePath, $netxCategoryPath);
	}

	/**
	 * Get connected user
	 *
	 * @return netxUser connnected user
	 */
	public function getUser() {
		return $this->connection->getUser();
	}

	/**
	 * Get connection object
	 *
	 * @return netxConnection connection object
	 */
	public function getConnection() {
		return $this->connection;
	}
}

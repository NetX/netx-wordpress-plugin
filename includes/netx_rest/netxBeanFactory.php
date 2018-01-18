<?php
/**
 * netxBeanFactory class file
 *
 * Contains definition for the netxBeanFactory class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Processes XML returned by various REST calls and returns appropriate objects
 *
 * @package NetxRestAPI
 */
class netxBeanFactory {
	/**
	 * Parse XML document returned by call to retrieve category tree
	 *
	 * @param string $xmlstr xml string returned by REST call
	 * @return array category tree
	 */
	public static function parseCategoryTree($xmlstr) {
		$xml = new SimpleXMLElement($xmlstr);
		$treeRoot = array();
		$treeRoot['1']['children'] = netxBeanFactory::processNodeList($xml);
		return $treeRoot;
	}

	/**
	 * Process list of category nodes
	 *
	 * @param SimpleXMLElement $nodeList nodes in category tree
	 * @return array category tree
	 */
	private static function processNodeList($nodeList) {
		$tree = array();
		foreach ($nodeList as $node) {
			$newNode = netxBeanFactory::parseCategoryNode($node);
			if (count($node->children()) > 0) {
				$newNode['children'] = netxBeanFactory::processNodeList($node->children());
			}
			$tree[$newNode['categoryId']] = $newNode;
		}
		return $tree;
	}

	/**
	 * Create an array for a category node
	 *
	 * @param SimpleXMLElement $node node in category tree
	 * @return array category node
	 */
	private static function parseCategoryNode($node) {
		$n = array();
		$n['label'] = (string)$node['label'];
		$n['categoryId'] = (string)$node['categoryId'];
		$n['parentId'] = (string)$node['parentId'];
		$n['hasAssets'] = (string)$node['hasAssets'];
		$n['longestKid'] = (string)$node['longestKid'];
		$n['path'] = (string)$node['path'];
		$n['matrix'] = (string)$node['matrix'];
		$n['children'] = array();
		return $n;
	}

	/**
	 * Parse AssetList XML
	 *
	 * @param string $xmlstr xml string returned by REST call
	 * @return array array of netxAssetList objects
	 */
	public static function parseAssetListXML($xmlstr) {
		$assetListArray = array();
		$xml = new SimpleXMLElement($xmlstr);
		foreach ($xml->array->void as $assetListItem) {
			$idx = (int)$assetListItem['index'];
			$properties = array();
			foreach ($assetListItem->object->void as $property) {
				$propertyName = (string)$property['property'];
				if ($property->string) {
					$propertyValue = (string)$property->string;
				} else if ($property->int) {
					$propertyValue = (int)$property->int;
				} else if ($property->boolean) {
					$propertyValue = ((((string)$property->boolean) == 'true') ? true : 'false');
				} else if ($property->array) {
					$ar = netxBeanFactory::createArray($property->array);
					$propertyValue = $ar;
				}
				$properties[$propertyName] = $propertyValue;
			}
			$assetList = netxBeanFactory::createAssetListBean($properties);
			array_push($assetListArray, $assetList);
		}
		return $assetListArray;
	}

	/**
	 * Parse category XML
	 *
	 * @param string $xmlstr xml string returned by REST call
	 * @return array array of netxCategory objects
	 */
	public static function parseCategoryXML($xmlstr) {
		$catList = array();
		$xml = new SimpleXMLElement($xmlstr);
		foreach ($xml->array->void as $catObjectItem) {
			$idx = (int)$catObjectItem['index'];
			$properties = array();
			foreach ($catObjectItem->object->void as $property) {
				$propertyName = (string)$property['property'];
				if ($property->string) {
					$propertyValue = (string)$property->string;
				} else if ($property->int) {
					$propertyValue = (int)$property->int;
				} else if ($property->boolean) {
					$propertyValue = ((((string)$property->boolean) == 'true') ? true : 'false');
				} else if ($property->array) {
					$ar = netxBeanFactory::createArray($property->array);
					$propertyValue = $ar;
				}
				$properties[$propertyName] = $propertyValue;
			}
			$cat = netxBeanFactory::createCategoryBean($properties);
			array_push($catList, $cat);
		}
		return $catList;
	}

	/**
	 * Parse javabean array
	 *
	 * @param SimpleXMLElement $arrayEl element that contains array
	 * @return array PHP array
	 */
	private static function createArray($arrayEl) {
		$ar = array();
		$type = (string)$arrayEl['class'];
		$length = (int)$arrayEl['length'];
		foreach ($arrayEl->void as $item) {
			$itemIdx = (int)$item['index'];
			if ($item->int) {
				$itemVal = (int)$item->int;
			} else if ($item->string) {
				$itemVal = (string)$item->string;
			}
			$ar[$itemIdx] = $itemVal;
		}
		return $ar;
	}

	/**
	 * Parse bean XML returned from the REST API
	 *
	 * @param string $xmlstr xml string returned by REST call
	 * @return mixed object of correct type depending on XML input
	 */
	public static function parseBeanXML($xmlstr) {
		try {
			$xml = new SimpleXMLElement($xmlstr);
			$beanType = $xml->object['class'];
			$idx = strrpos($beanType, '.') + 1;
			$class = substr($beanType, $idx);
			$properties = array();
			foreach ($xml->object->void as $property) {
				$propertyName = (string)$property['property'];
				if ($property->string) {
					$propertyValue = (string)$property->string;
				} else if ($property->int) {
					$propertyValue = (int)$property->int;
				} else if ($property->long) {
					$propertyValue = (int)$property->long;
				} else if ($property->boolean) {
					$propertyValue = ((((string)$property->boolean) == 'true') ? true : 'false');
				} else if ($property->array) {
					$ar = netxBeanFactory::createArray($property->array);
					$propertyValue = $ar;
				}
				$properties[$propertyName] = $propertyValue;
			}
			switch ($class) {
				case 'UserBean':
					return netxBeanFactory::createUserBean($properties);
					break;
				case 'CategoryBean':
					return netxBeanFactory::createCategoryBean($properties);
					break;
				case 'AssetBean':
					return netxBeanFactory::createAssetBean($properties);
					break;
			}
		} catch (Exception $parsingException) {
			echo $parsingException;
			echo "\n\n";
			echo "Input:\n\n";
			echo $xmlstr;
			exit;
		}
	}

	/**
	 * Creates a new netxAsset from the API call XML
	 *
	 * @param array $properties array of properties snarfed from the XML
	 * @return netxAsset new category object
	 */
	private static function createAssetBean($properties) {
		$ab = new netxAsset();
		if (isset($properties['assetId'])) {
			$ab->setAssetID($properties['assetId']);
		}
		if (isset($properties['attribute1'])) {
			$ab->setAttribute19($properties['attribute1']);
		}
		if (isset($properties['attribute2'])) {
			$ab->setAttribute19($properties['attribute2']);
		}
		if (isset($properties['attribute3'])) {
			$ab->setAttribute19($properties['attribute3']);
		}
		if (isset($properties['attribute4'])) {
			$ab->setAttribute19($properties['attribute4']);
		}
		if (isset($properties['attribute5'])) {
			$ab->setAttribute19($properties['attribute5']);
		}
		if (isset($properties['attribute6'])) {
			$ab->setAttribute19($properties['attribute6']);
		}
		if (isset($properties['attribute7'])) {
			$ab->setAttribute19($properties['attribute7']);
		}
		if (isset($properties['attribute8'])) {
			$ab->setAttribute19($properties['attribute8']);
		}
		if (isset($properties['attribute9'])) {
			$ab->setAttribute19($properties['attribute9']);
		}
		if (isset($properties['attribute10'])) {
			$ab->setAttribute19($properties['attribute10']);
		}
		if (isset($properties['attribute11'])) {
			$ab->setAttribute19($properties['attribute11']);
		}
		if (isset($properties['attribute12'])) {
			$ab->setAttribute19($properties['attribute12']);
		}
		if (isset($properties['attribute13'])) {
			$ab->setAttribute19($properties['attribute13']);
		}
		if (isset($properties['attribute14'])) {
			$ab->setAttribute19($properties['attribute14']);
		}
		if (isset($properties['attribute15'])) {
			$ab->setAttribute19($properties['attribute15']);
		}
		if (isset($properties['attribute16'])) {
			$ab->setAttribute19($properties['attribute16']);
		}
		if (isset($properties['attribute17'])) {
			$ab->setAttribute19($properties['attribute17']);
		}
		if (isset($properties['attribute18'])) {
			$ab->setAttribute19($properties['attribute18']);
		}
		if (isset($properties['attribute19'])) {
			$ab->setAttribute19($properties['attribute19']);
		}
		if (isset($properties['attribute20'])) {
			$ab->setAttribute20($properties['attribute20']);
		}
		if (isset($properties['attributeIds'])) {
			$ab->setAttributeIds($properties['attributeIds']);
		}
		if (isset($properties['attributeNames'])) {
			$ab->setAttributeNames($properties['attributeNames']);
		}
		if (isset($properties['attributeValues'])) {
			$ab->setAttributeValues($properties['attributeValues']);
		}
		if (isset($properties['categories'])) {
			$ab->setCategories($properties['categories']);
		}
		if (isset($properties['categoryid'])) {
			$ab->setCategoryid($properties['categoryid']);
		}
		if (isset($properties['creationdate'])) {
			$ab->setCreationdate($properties['creationdate']);
		}
		if (isset($properties['description'])) {
			$ab->setDescription($properties['description']);
		}
		if (isset($properties['file'])) {
			$ab->setFile($properties['file']);
		}
		if (isset($properties['fileSize2'])) {
			$ab->setFileSize2($properties['fileSize2']);
		}
		if (isset($properties['fileheight'])) {
			$ab->setFileheight($properties['fileheight']);
		}
		if (isset($properties['filesize'])) {
			$ab->setFilesize($properties['filesize']);
		}
		if (isset($properties['filetype'])) {
			$ab->setFiletype($properties['filetype']);
		}
		if (isset($properties['filetypelabel'])) {
			$ab->setFiletypelabel($properties['filetypelabel']);
		}
		if (isset($properties['filewidth'])) {
			$ab->setFilewidth($properties['filewidth']);
		}
		if (isset($properties['locationContext'])) {
			$ab->setLocationContext($properties['locationContext']);
		}
		if (isset($properties['locationid'])) {
			$ab->setLocationid($properties['locationid']);
		}
		if (isset($properties['moddate'])) {
			$ab->setModdate($properties['moddate']);
		}
		if (isset($properties['moduserid'])) {
			$ab->setModuserid($properties['moduserid']);
		}
		if (isset($properties['moduserlabel'])) {
			$ab->setModuserlabel($properties['moduserlabel']);
		}
		if (isset($properties['name'])) {
			$ab->setName($properties['name']);
		}
		if (isset($properties['path'])) {
			$ab->setPath($properties['path']);
		}
		if (isset($properties['permissionMatrix'])) {
			$ab->setPermissionMatrix($properties['permissionMatrix']);
		}
		if (isset($properties['previewUrl'])) {
			$ab->setPreviewUrl($properties['previewUrl']);
		}
		if (isset($properties['repurposeAvailability'])) {
			$ab->setRepurposeAvailability($properties['repurposeAvailability']);
		}
		if (isset($properties['status'])) {
			$ab->setStatus($properties['status']);
		}
		if (isset($properties['thumb'])) {
			$ab->setThumb($properties['thumb']);
		}
		if (isset($properties['thumbUrl'])) {
			$ab->setThumbUrl($properties['thumbUrl']);
		}
		if (isset($properties['zoomAvailable'])) {
			$ab->setZoomAvailable($properties['zoomAvailable']);
		}
		if (isset($properties['viewIds'])) {
			$ab->setViewIds($properties['viewIds']);
		}
		if (isset($properties['viewNames'])) {
			$ab->setViewNames($properties['viewNames']);
		}
		if (isset($properties['constituentType'])) {
			$ab->setConstituentType($properties['constituentType']);
		}
		if (isset($properties['constituentIds'])) {
			$ab->setConstituentIds($properties['constituentIds']);
		}

		return $ab;
	}

	/**
	 * Creates a new netxCategory from the API call XML
	 *
	 * @param array $properties array of properties snarfed from the XML
	 * @return netxCategory new category object
	 */
	private static function createCategoryBean($properties) {
		$cb = new netxCategory();
		$cb->setCategoryID($properties['categoryid']);
		$cb->setContents($properties['contents']);
		$cb->setCreationDate($properties['creationDate']);
		if (isset($properties['moduserlabel'])) {
			$cb->setModuserLabel($properties['moduserlabel']);
		}
		$cb->setName($properties['name']);
		$cb->setOrderBy($properties['orderby']);
		$cb->setParentCategoryName($properties['parentCategoryName']);
		$cb->setParentID($properties['parentid']);
		$cb->setPath($properties['path']);
		$cb->setPermissionMatrix($properties['permissionMatrix']);
		$cb->setRepositoryDirectory($properties['repositorydirectory']);
		if (isset($properties['userId'])) {
			$cb->setUserID($properties['userId']);
		}
		$cb->setVisible($properties['visible']);
		if (isset($properties['type'])) {
			$cb->setCategoryType($properties['type']);
		}
		if (isset($properties['hasContents'])) {
			$cb->setHasContents($properties['hasContents']);
		}
		if (isset($properties['children'])) {
			$cb->setChildren($properties['children']);
		}
		return $cb;
	}

	/**
	 * Creates a new netxUser from the API call XML
	 *
	 * @param array $properties array of properties snarfed from the XML
	 * @return netxUser new user object
	 */
	private static function createUserBean($properties) {
		$ub = new netxUser();
		$ub->setAddress1($properties['address1']);
		$ub->setAddress2($properties['address2']);
		$ub->setCity($properties['city']);
		$ub->setCountry($properties['country']);
		$ub->setEmail($properties['email']);
		$ub->setLogin($properties['login']);
		$ub->setName1($properties['name1']);
		$ub->setName2($properties['name2']);
		$ub->setName3($properties['name3']);
		$ub->setName4($properties['name4']);
		$ub->setOrganization($properties['organization']);
		$ub->setPhone1($properties['phone1']);
		$ub->setPhone2($properties['phone2']);
		$ub->setPhone3($properties['phone3']);
		$ub->setPhone4($properties['phone4']);
		$ub->setState($properties['state']);
		if (isset($properties['type'])) {
			$ub->setType($properties['type']);
		}
		$ub->setUserID($properties['userid']);
		$ub->setZip($properties['zip']);

		return $ub;
	}

	/**
	 * Creates a new netxAssetList from the API call XML
	 *
	 * @param array $properties array of properties snarfed from the XML
	 * @return netxAssetList new AssetList object
	 */
	private static function createAssetListBean($properties) {
		$al = new netxAssetList();
		$al->setAssetID($properties['assetId']);
		$al->setLabel1($properties['label1']);
		$al->setLabel2($properties['label2']);
		$al->setLabel3($properties['label3']);
		$al->setLabel4($properties['label4']);
		$al->setLabel5($properties['label5']);
		$al->setPreview($properties['preview']);
		$al->setThumb($properties['thumb']);

		return $al;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxBeanFactory Object                          |' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}

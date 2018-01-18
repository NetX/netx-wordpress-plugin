<?php
/**
 * netxCategory class file
 *
 * Contains definition for the netxCategory class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Corresponds to CategoryBean
 *
 * @package NetxRestAPI
 */
class netxCategory extends netxBean {
	const STATUS_UNKNOWN = 0;
	const STATUS_ONLINE = 1;
	const STATUS_NEARLINE = 2;
	const STATUS_OFFLINE = 3;
	const STATUS_TEMP = 4;
	const STATUS_CHECKOUT = 5;

	const NOT_VISIBLE = 0;
	const VISIBLE = 1;

	/**
	 * Category ID
	 * @var int Category ID
	 * @access private
	 */
	private $categoryid = 0;

	/**
	 * Content type of assets in category
	 * @var string
	 * @access private
	 */
	private $contents = '';

	/**
	 * Category creation date
	 * @var string
	 * @access private
	 */
	private $creationDate = '';

	/**
	 * Creation date converted to Unix timestamp
	 * @var int creationDateTS
	 * @access private
	 */
	private $creationDateTS = 0;

	/**
	 * Indicates whether there is anything in the category
	 * @var boolean
	 * @access private
	 */
	private $hasContents = false;

	/**
	 * User label
	 * @var string
	 * @access private
	 */
	private $moduserlabel = '';

	/**
	 * Category name
	 * @var string
	 * @access private
	 */
	private $name = '';

	/**
	 * Category order by attribute
	 * @var int
	 * @access private
	 */
	private $orderby = 0;

	/**
	 * Name of parent category
	 * @var string
	 * @access private
	 */
	private $parentCategoryName = '';

	/**
	 * ID of parent category
	 * @var int
	 * @access private
	 */
	private $parentid = 0;

	/**
	 * Path attribute (i.e. cat1 >> cat2 >> cat3)
	 * @var string
	 * @access private
	 */
	private $path = '';

	/**
	 * Category permission matrix
	 * @var array
	 * @access private
	 */
	private $permissionMatrix = array();

	/**
	 * Child category IDs
	 * @var array
	 * @access private
	 */
	private $children = array();

	/**
	 * Category repository directory (i.e. cat1/cat2/cat3)
	 * @var string
	 * @access private
	 */
	private $repositorydirectory = '';

	/**
	 * User ID of category owner
	 * @var int
	 * @access private
	 */
	private $userId = 0;

	/**
	 * Category type
	 * @var int
	 * @access private
	 */
	private $type = 0;

	/**
	 * Category visible attribute
	 * 0 == Not visible
	 * 1 == Visible
	 * @var int
	 * @access private
	 */
	private $visible = 0;

	/**
	 * Constructor
	 *
	 * @return netxCategory
	 */
	public function __construct() {
	}

	/**
	 * Set the category ID
	 *
	 * @param int $categoryID Category ID
	 */
	public function setCategoryID($categoryID) {
		$this->categoryid = $categoryID;
	}

	/**
	 * Get the category ID
	 *
	 * @return int Category ID
	 */
	public function getCategoryID() {
		return $this->categoryid;
	}

	/**
	 * Set the category type
	 *
	 * @param int $type Category type
	 */
	public function setCategoryType($type) {
		$this->type = $type;
	}

	/**
	 * Get the category type
	 *
	 * @return int Category type
	 */
	public function getCategoryType() {
		return $this->type;
	}

	/**
	 * Set the category contents value
	 *
	 * @param int $contents Category contents
	 */
	public function setContents($contents) {
		$this->contents = $contents;
	}

	/**
	 * Get the category contents value
	 *
	 * @return string Category contents
	 */
	public function getContents() {
		return $this->contents;
	}

	/**
	 * Set the category creation date
	 *
	 * @param string $creationDate Category creation date
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
		$this->creationDateTS = $this->mkTimestamp($creationDate);
	}

	/**
	 * Get the category creation date
	 *
	 * @return string Category creation date
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * Get creation date as Unix timestamp
	 *
	 * @return int creation timestamp
	 */
	public function getCreationDateTS() {
		return $this->creationDateTS;
	}

	/**
	 * Get formatted creation date date
	 *
	 * Passes the format string directly to PHP's date() function, so
	 * any formatting valid for the date function can be given here.
	 *
	 * @link http://www.php.net/manual/en/function.date.php
	 * @param string $dateFormat date format string
	 * @return string create date
	 */
	public function getCreationDateFormatted($dateFormat = 'c') {
		return $this->getFormattedDate($this->creationDateTS, $dateFormat);
	}

	/**
	 * Set whether the category contains objects or not
	 *
	 * @param boolean $hasContents Category has contents
	 */
	public function setHasContents($hasContents) {
		$this->hasContents = $hasContents;
	}

	/**
	 * Get whether the category contains objects or not
	 *
	 * @return boolean Category has contents
	 */
	public function hasContents() {
		return $this->hasContents;
	}

	/**
	 * Set the category user label
	 *
	 * @param string $modUserLabel Category user label
	 */
	public function setModuserLabel($modUserLabel) {
		$this->moduserlabel = $modUserLabel;
	}

	/**
	 * Get the category user label
	 *
	 * @return string Category user label
	 */
	public function getModuserLabel() {
		return $this->moduserlabel;
	}

	/**
	 * Set the category name
	 *
	 * @param string $name Category name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get the category name
	 *
	 * @return string Category name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the category order by attribute
	 *
	 * @param int $orderby Category order by attribute
	 */
	public function setOrderBy($orderby) {
		$this->orderby = $orderby;
	}

	/**
	 * Get the category order by attribute
	 *
	 * @return int Category order by attribute
	 */
	public function getOrderBy() {
		return $this->orderby;
	}

	/**
	 * Set the parent category name
	 *
	 * @param string $parentName parent category name
	 */
	public function setParentCategoryName($parentName) {
		$this->parentCategoryName = $parentName;
	}

	/**
	 * Get the parent category name
	 *
	 * @return string parent category name
	 */
	public function getParentCategoryName() {
		return $this->parentCategoryName;
	}

	/**
	 * Set the parent category ID
	 *
	 * @param int $parentID Parent category ID
	 */
	public function setParentID($parentID) {
		$this->parentid = $parentID;
	}

	/**
	 * Get the parent category ID
	 *
	 * @return int Parent category ID
	 */
	public function getParentID() {
		return $this->parentid;
	}

	/**
	 * Set the category path (i.e. cat1 >> cat2 >> cat3)
	 *
	 * @param int $path Category path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Get the category path (i.e. cat1 >> cat2 >> cat3)
	 *
	 * @return int Category path
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Set the category permission matrix
	 *
	 * @param array $permissionMatrix Category permission matrix
	 */
	public function setPermissionMatrix($permissionMatrix) {
		$this->permissionMatrix = $permissionMatrix;
	}

	/**
	 * Get the category permission matrix
	 *
	 * @return array Category permission matrix
	 */
	public function getPermissionMatrix() {
		return $this->permissionMatrix;
	}

	/**
	 * Set the children ID array
	 *
	 * @param array $children array of child category IDs
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * Get the children ID array
	 *
	 * @return array array of child category IDs
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * Set the category repository directory (i.e. cat1/cat2/cat3)
	 *
	 * @param string $repositoryDirectory Category repository directory
	 */
	public function setRepositoryDirectory($repositoryDirectory) {
		$this->repositorydirectory = $repositoryDirectory;
	}

	/**
	 * Get the category repository directory (i.e. cat1/cat2/cat3)
	 *
	 * @return string Category repository directory
	 */
	public function getRepositoryDirectory() {
		return $this->repositorydirectory;
	}

	/**
	 * Set the category owner user ID
	 *
	 * @param int $userID Category owner user ID
	 */
	public function setUserID($userID) {
		$this->userId = $userID;
	}

	/**
	 * Get the category owner user ID
	 *
	 * @return int Category owner user ID
	 */
	public function getUserID() {
		return $this->userId;
	}

	/**
	 * Set the category visible attribute
	 *
	 * @param int $visible Category visible attribute
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
	}

	/**
	 * Get the category visible attribute
	 *
	 * @return int Category visible attribute
	 */
	public function getVisible() {
		return $this->visible;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$pm = '';
		$first = true;
		foreach ($this->permissionMatrix as $idx => $val) {
			if (!$first) {
				$pm .= ', ';
			} else {
				$first = false;
			}
			$pm .= "$idx -> $val";
		}
		$ch = '';
		$first = true;
		foreach ($this->children as $idx => $val) {
			if (!$first) {
				$ch .= ', ';
			} else {
				$first = false;
			}
			$ch .= "$val";
		}
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxCategory Object                             |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '         Category ID: ' . $this->categoryid . "\n";
		$ostr .= '            Contents: ' . $this->contents . "\n";
		$ostr .= '       Creation Date: ' . $this->creationDate . "\n";
		$ostr .= '        Has Contents: ' . (($this->hasContents) ? 'true' : 'false') . "\n";
		$ostr .= '      Mod User Label: ' . $this->moduserlabel . "\n";
		$ostr .= '                Name: ' . $this->name . "\n";
		$ostr .= '            Order By: ' . $this->orderby . "\n";
		$ostr .= 'Parent Category Name: ' . $this->parentCategoryName . "\n";
		$ostr .= '           Parent ID: ' . $this->parentid . "\n";
		$ostr .= '                Path: ' . $this->path . "\n";
		$ostr .= '   Permission Matrix: ' . $pm . "\n";
		$ostr .= 'Repository Directory: ' . $this->repositorydirectory . "\n";
		$ostr .= '             User ID: ' . $this->userId . "\n";
		$ostr .= '             Visible: ' . $this->visible . "\n";
		$ostr .= '                Type: ' . $this->type . "\n";
		$ostr .= '            Children: ' . $ch . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}

	/**
	 * Returns JSON representation of object
	 *
	 * @return string JSON representation of the object
	 */
	public function toJson() {
		return json_encode($this->toArray());
	}

	/**
	 * Returns array representation of object
	 *
	 * @return array Array representation of the object
	 */
	public function toArray() {
		$out = array();

		$out['categoryid'] = $this->categoryid;
		$out['contents'] = $this->contents;
		$out['creationDate'] = $this->creationDate;
		$out['hasContents'] = (($this->hasContents) ? 'true' : 'false');
		$out['moduserlabel'] = $this->moduserlabel;
		$out['name'] = $this->name;
		$out['orderby'] = $this->orderby;
		$out['parentCategoryName'] = $this->parentCategoryName;

		$out['parentid'] = $this->parentid;
		$out['path'] = $this->path;
		$out['permissionmatrix'] = $this->pm;
		$out['repositorydirectory'] = $this->repositorydirectory;
		$out['userId'] = $this->userId;
		$out['visible'] = $this->visible;
		$out['type'] = $this->type;
		$out['children'] = $this->ch;

		return $out;
	}
}

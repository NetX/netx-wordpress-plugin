<?php
/**
 * netxAsset class file
 *
 * Contains definition for the netxAsset class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Corresponds to AssetBean
 *
 * @package NetxRestAPI
 */
class netxAsset extends netxBean {
	const STATUS_UNKNOWN = 0;
	const STATUS_ONLINE = 1;
	const STATUS_NEARLINE = 2;
	const STATUS_OFFLINE = 3;
	const STATUS_TEMP = 4;
	const STATUS_CHECKOUT = 5;

	const NOT_VISIBLE = 0;
	const VISIBLE = 1;

	const REPURPOSE_AVAILABILITY_DOCUMENT = 3;
	const REPURPOSE_AVAILABILITY_ERROR = -1;
	const REPURPOSE_AVAILABILITY_IMAGE = 1;
	const REPURPOSE_AVAILABILITY_NONE = 0;
	const REPURPOSE_AVAILABILITY_VIDEO = 2;

	/**
	 * Asset IDs
	 * @var array assetId
	 * @access private
	 */
	private $assetId = 0;

	/**
	 * Attribute 1
	 * @var string attribute1
	 * @access private
	 */
	private $attribute1 = "";

	/**
	 * Attribute 2
	 * @var string attribute2
	 * @access private
	 */
	private $attribute2 = '';

	/**
	 * Attribute 3
	 * @var string attribute3
	 * @access private
	 */
	private $attribute3 = '';

	/**
	 * Attribute 4
	 * @var string attribute4
	 * @access private
	 */
	private $attribute4 = '';

	/**
	 * Attribute 5
	 * @var string attribute5
	 * @access private
	 */
	private $attribute5 = '';

	/**
	 * Attribute 6
	 * @var string attribute6
	 * @access private
	 */
	private $attribute6 = '';

	/**
	 * Attribute 7
	 * @var string attribute7
	 * @access private
	 */
	private $attribute7 = '';

	/**
	 * Attribute 8
	 * @var string attribute8
	 * @access private
	 */
	private $attribute8 = '';

	/**
	 * Attribute 9
	 * @var string attribute9
	 * @access private
	 */
	private $attribute9 = '';

	/**
	 * Attribute 10
	 * @var string attribute10
	 * @access private
	 */
	private $attribute10 = '';

	/**
	 * Attribute 11
	 * @var string attribute11
	 * @access private
	 */
	private $attribute11 = '';

	/**
	 * Attribute 12
	 * @var string attribute12
	 * @access private
	 */
	private $attribute12 = '';

	/**
	 * Attribute 13
	 * @var string attribute13
	 * @access private
	 */
	private $attribute13 = '';

	/**
	 * Attribute 14
	 * @var string attribute14
	 * @access private
	 */
	private $attribute14 = '';

	/**
	 * Attribute 15
	 * @var string attribute15
	 * @access private
	 */
	private $attribute15 = '';

	/**
	 * Attribute 16
	 * @var string attribute16
	 * @access private
	 */
	private $attribute16 = '';

	/**
	 * Attribute 17
	 * @var string attribute17
	 * @access private
	 */
	private $attribute17 = '';

	/**
	 * Attribute 18
	 * @var string attribute18
	 * @access private
	 */
	private $attribute18 = '';

	/**
	 * Attribute 19
	 * @var string attribute19
	 * @access private
	 */
	private $attribute19 = '';

	/**
	 * Attribute 20
	 * @var string attribute20
	 * @access private
	 */
	private $attribute20 = '';

	/**
	 * Attribute IDs
	 * @var array attributeIds
	 * @access private
	 */
	private $attributeIds = array();

	/**
	 * Attribute Names
	 * @var array attributeNames
	 * @access private
	 */
	private $attributeNames = array();

	/**
	 * Attribute Values
	 * @var array attributeValues
	 * @access private
	 */
	private $attributeValues = array();

	/**
	 * Categories
	 * @var array categories
	 * @access private
	 */
	private $categories = array();

	/**
	 * Category ID
	 * @var int categoryid
	 * @access private
	 */
	private $categoryid = 0;

	/**
	 * Creation date
	 * @var string creationdate
	 * @access private
	 */
	private $creationdate = '';

	/**
	 * Creation date converted to Unix timestamp
	 * @var int creationDateTS
	 * @access private
	 */
	private $creationDateTS = 0;

	/**
	 * description
	 * @var string description
	 * @access private
	 */
	private $description = '';

	/**
	 * file
	 * @var string file
	 * @access private
	 */
	private $file = '';

	/**
	 * Filesize2 Property
	 * @var long fileSize2
	 * @access private
	 */
	private $fileSize2 = 0;

	/**
	 * Image height
	 * @var int fileheight
	 * @access private
	 */
	private $fileheight = 0;

	/**
	 * File size
	 * @var int filesize
	 * @access private
	 */
	private $filesize = 0;

	/**
	 * File type
	 * @var int filetype
	 * @access private
	 */
	private $filetype = 0;

	/**
	 * File type label
	 * @var string filetypelabel
	 * @access private
	 */
	private $filetypelabel = '';

	/**
	 * Image width
	 * @var int filewidth
	 * @access private
	 */
	private $filewidth = 0;

	/**
	 * locationContext
	 * @var string locationContext
	 * @access private
	 */
	private $locationContext = '';

	/**
	 * locationid
	 * @var int locationid
	 * @access private
	 */
	private $locationid = 0;

	/**
	 * moddate
	 * @var string moddate
	 * @access private
	 */
	private $moddate = '';

	/**
	 * Mod date converted to Unix timestamp
	 * @var int modDateTS
	 * @access private
	 */
	private $modDateTS = 0;

	/**
	 * moduserid
	 * @var int moduserid
	 * @access private
	 */
	private $moduserid = 0;

	/**
	 * moduserlabel
	 * @var string moduserlabel
	 * @access private
	 */
	private $moduserlabel = '';

	/**
	 * Name
	 * @var string name
	 * @access private
	 */
	private $name = '';

	/**
	 * Path
	 * @var string path
	 * @access private
	 */
	private $path = '';

	/**
	 * Permission matrix
	 * @var array permission matrix
	 * @access private
	 */
	private $permissionMatrix = array();

	/**
	 * Image preview URI
	 * @var string preview URI
	 * @access private
	 */
	private $previewUrl = '';

	/**
	 * repurpose availability property
	 * @var int repurpose availability property
	 * @access private
	 */
	private $repurposeAvailability = 0;

	/**
	 * Status
	 * @var int status
	 * @access private
	 */
	private $status = 0;

	/**
	 * Thumbnail
	 * @var string thumbnail
	 * @access private
	 */
	private $thumb = '';

	/**
	 * Thumbnail URI
	 * @var string thumbnail URI
	 * @access private
	 */
	private $thumbUrl = '';

	/**
	 * zoom available
	 * @var boolean zoom available
	 * @access private
	 */
	private $zoomAvailable = false;

	/**
	 * View IDs
	 * @var array viewIds
	 * @access private
	 */
	private $viewIds = array();

	/**
	 * View names
	 * @var array viewNames
	 * @access private
	 */
	private $viewNames = array();

	/**
	 * Constituent IDs
	 * @var array constituentIds
	 * @access private
	 */
	private $constituentIds = array();

	/**
	 * Constituent type (page or keyframe)
	 * @var array constituentType
	 * @access private
	 */
	private $constituentType = '';

	/**
	 * Constructor
	 *
	 * @return netxAsset
	 */
	public function __construct() {
	}

	/**
	 * Set asset ID
	 *
	 * @param string $assetId asset ID
	 */
	public function setAssetID($assetId) {
		$this->assetId = $assetId;
	}

	/**
	 * Set attribute 1
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 1
	 */
	public function setAttribute1($attribute1) {
		$this->attribute1 = $attribute1;
	}

	/**
	 * Set attribute 2
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 2
	 */
	public function setAttribute2($attribute2) {
		$this->attribute2 = $attribute2;
	}

	/**
	 * Set attribute 3
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 3
	 */
	public function setAttribute3($attribute3) {
		$this->attribute3 = $attribute3;
	}

	/**
	 * Set attribute 4
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 4
	 */
	public function setAttribute4($attribute4) {
		$this->attribute4 = $attribute4;
	}

	/**
	 * Set attribute 5
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 5
	 */
	public function setAttribute5($attribute5) {
		$this->attribute5 = $attribute5;
	}

	/**
	 * Set attribute 6
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 6
	 */
	public function setAttribute6($attribute6) {
		$this->attribute6 = $attribute6;
	}

	/**
	 * Set attribute 7
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 7
	 */
	public function setAttribute7($attribute7) {
		$this->attribute7 = $attribute7;
	}

	/**
	 * Set attribute 8
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 8
	 */
	public function setAttribute8($attribute8) {
		$this->attribute8 = $attribute8;
	}

	/**
	 * Set attribute 9
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 9
	 */
	public function setAttribute9($attribute9) {
		$this->attribute9 = $attribute9;
	}

	/**
	 * Set attribute 10
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 10
	 */
	public function setAttribute10($attribute10) {
		$this->attribute10 = $attribute10;
	}

	/**
	 * Set attribute 11
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 11
	 */
	public function setAttribute11($attribute11) {
		$this->attribute11 = $attribute11;
	}

	/**
	 * Set attribute 12
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 12
	 */
	public function setAttribute12($attribute12) {
		$this->attribute12 = $attribute12;
	}

	/**
	 * Set attribute 13
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 13
	 */
	public function setAttribute13($attribute13) {
		$this->attribute13 = $attribute13;
	}

	/**
	 * Set attribute 14
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 14
	 */
	public function setAttribute14($attribute14) {
		$this->attribute14 = $attribute14;
	}

	/**
	 * Set attribute 15
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 15
	 */
	public function setAttribute15($attribute15) {
		$this->attribute15 = $attribute15;
	}

	/**
	 * Set attribute 16
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 16
	 */
	public function setAttribute16($attribute16) {
		$this->attribute16 = $attribute16;
	}

	/**
	 * Set attribute 17
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 17
	 */
	public function setAttribute17($attribute17) {
		$this->attribute17 = $attribute17;
	}

	/**
	 * Set attribute 18
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 18
	 */
	public function setAttribute18($attribute18) {
		$this->attribute18 = $attribute18;
	}

	/**
	 * Set attribute 19
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute1 attribute 19
	 */
	public function setAttribute19($attribute19) {
		$this->attribute19 = $attribute19;
	}

	/**
	 * Set attribute 20
	 *
	 * @deprecated in the NetX API
	 * @param string $attribute20 attribute 20
	 */
	public function setAttribute20($attribute20) {
		$this->attribute20 = $attribute20;
	}

	/**
	 * Set attribute ids
	 *
	 * @param array $attributeIds attribute ids
	 */
	public function setAttributeIds($attributeIds) {
		$this->attributeIds = $attributeIds;
	}

	/**
	 * Set attribute names
	 *
	 * @param string $attributeNames attribute names
	 */
	public function setAttributeNames($attributeNames) {
		$this->attributeNames = $attributeNames;
	}

	/**
	 * Set attribute values
	 *
	 * @param array $attributeValues attribute values
	 */
	public function setAttributeValues($attributeValues) {
		$this->attributeValues = $attributeValues;
	}

	/**
	 * Set categories
	 *
	 * @param array $categories categories
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}

	/**
	 * Set category ID
	 *
	 * @param int $categoryid category ID
	 */
	public function setCategoryid($categoryid) {
		$this->categoryid = $categoryid;
	}

	/**
	 * Set creation date
	 *
	 * @param string $creationdate creation date
	 */
	public function setCreationdate($creationdate) {
		$this->creationdate = $creationdate;
		$this->creationDateTS = $this->mkTimestamp($creationdate);
	}

	/**
	 * Set description
	 *
	 * @param string $description description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Set file
	 *
	 * @param string $file file
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * Set file size 2
	 *
	 * @param long $fileSize2 file size 2
	 */
	public function setFileSize2($fileSize2) {
		$this->fileSize2 = $fileSize2;
	}

	/**
	 * Set image height
	 *
	 * @param int $fileheight image height
	 */
	public function setFileheight($fileheight) {
		$this->fileheight = $fileheight;
	}

	/**
	 * Set file size
	 *
	 * @param int $filesize file size
	 */
	public function setFilesize($filesize) {
		$this->filesize = $filesize;
	}

	/**
	 * Set file type
	 *
	 * @param int $filetype file type
	 */
	public function setFiletype($filetype) {
		$this->filetype = $filetype;
	}

	/**
	 * Set file type label
	 *
	 * @param string $filetypelabel file type label
	 */
	public function setFiletypelabel($filetypelabel) {
		$this->filetypelabel = $filetypelabel;
	}

	/**
	 * Set image width
	 *
	 * @param int $filewidth image width
	 */
	public function setFilewidth($filewidth) {
		$this->filewidth = $filewidth;
	}

	/**
	 * Set location context
	 *
	 * @param string $locationContext location context
	 */
	public function setLocationContext($locationContext) {
		$this->locationContext = $locationContext;
	}

	/**
	 * Set location id
	 *
	 * @param int $locationid location id
	 */
	public function setLocationid($locationid) {
		$this->locationid = $locationid;
	}

	/**
	 * Set mod date
	 *
	 * @param string $moddate mod date
	 */
	public function setModdate($moddate) {
		$this->moddate = $moddate;
		$this->modDateTS = $this->mkTimestamp($moddate);
	}

	/**
	 * Set moduserid
	 *
	 * @param int $moduserid moduserid
	 */
	public function setModuserid($moduserid) {
		$this->moduserid = $moduserid;
	}

	/**
	 * Set moduserlabel
	 *
	 * @param string $moduserlabel moduserlabel
	 */
	public function setModuserlabel($moduserlabel) {
		$this->moduserlabel = $moduserlabel;
	}

	/**
	 * Set name
	 *
	 * @param string $name name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Set file path
	 *
	 * @param string $path file path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Set permission matrix
	 *
	 * @param array $permissionMatrix permission matrix
	 */
	public function setPermissionMatrix($permissionMatrix) {
		$this->permissionMatrix = $permissionMatrix;
	}

	/**
	 * Set preview URI
	 *
	 * @param string $previewUrl preview URI
	 */
	public function setPreviewUrl($previewUrl) {
		$this->previewUrl = $previewUrl;
	}

	/**
	 * Set repurpose availability property
	 *
	 * @param int $repurposeAvailability repurpose availability
	 */
	public function setRepurposeAvailability($repurposeAvailability) {
		$this->repurposeAvailability = $repurposeAvailability;
	}

	/**
	 * Set status
	 *
	 * @param int $status status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * Set thumbnail
	 *
	 * @param string $thumb thumbnail
	 */
	public function setThumb($thumb) {
		$this->thumb = $thumb;
	}

	/**
	 * Set thumbnail URI
	 *
	 * @param string $thumbUrl thumbnail URI
	 */
	public function setThumbUrl($thumbUrl) {
		$this->thumbUrl = $thumbUrl;
	}

	/**
	 * Set zoom available
	 *
	 * @param boolean $zoomAvailable zoom available
	 */
	public function setZoomAvailable($zoomAvailable) {
		$this->zoomAvailable = $zoomAvailable;
	}

	/**
	 * Set view ID array
	 *
	 * @param array $viewIds array of view IDs
	 */
	public function setViewIds($viewIds) {
		$this->viewIds = $viewIds;
	}

	/**
	 * Set view name array
	 *
	 * @param array $viewNames array of view names
	 */
	public function setViewNames($viewNames) {
		$this->viewNames = $viewNames;
	}

	/**
	 * Set constituent ID array
	 *
	 * @param array $constituentIds array of view IDs
	 */
	public function setConstituentIds($constituentIds) {
		$this->constituentIds = $constituentIds;
	}

	/**
	 * Set constituent type
	 *
	 * @param array $constituentType constituent type (page|keyframe)
	 */
	public function setConstituentType($constituentType) {
		$this->constituentType = $constituentType;
	}

	/**
	 * Get asset ID
	 *
	 * @return int asset ID
	 */
	public function getAssetID() {
		return $this->assetId;
	}

	/**
	 * Get attribute 1
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 1
	 */
	public function getAttribute1() {
		return $this->attribute1;
	}

	/**
	 * Get attribute 2
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 2
	 */
	public function getAttribute2() {
		return $this->attribute2;
	}

	/**
	 * Get attribute 3
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 3
	 */
	public function getAttribute3() {
		return $this->attribute3;
	}

	/**
	 * Get attribute 4
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 4
	 */
	public function getAttribute4() {
		return $this->attribute4;
	}

	/**
	 * Get attribute 5
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 5
	 */
	public function getAttribute5() {
		return $this->attribute5;
	}

	/**
	 * Get attribute 6
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 6
	 */
	public function getAttribute6() {
		return $this->attribute6;
	}

	/**
	 * Get attribute 7
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 7
	 */
	public function getAttribute7() {
		return $this->attribute7;
	}

	/**
	 * Get attribute 8
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 8
	 */
	public function getAttribute8() {
		return $this->attribute8;
	}

	/**
	 * Get attribute 9
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 9
	 */
	public function getAttribute9() {
		return $this->attribute9;
	}

	/**
	 * Get attribute 10
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 10
	 */
	public function getAttribute10() {
		return $this->attribute10;
	}

	/**
	 * Get attribute 11
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 11
	 */
	public function getAttribute11() {
		return $this->attribute11;
	}

	/**
	 * Get attribute 12
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 12
	 */
	public function getAttribute12() {
		return $this->attribute12;
	}

	/**
	 * Get attribute 13
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 13
	 */
	public function getAttribute13() {
		return $this->attribute13;
	}

	/**
	 * Get attribute 14
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 14
	 */
	public function getAttribute14() {
		return $this->attribute14;
	}

	/**
	 * Get attribute 15
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 15
	 */
	public function getAttribute15() {
		return $this->attribute15;
	}

	/**
	 * Get attribute 16
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 16
	 */
	public function getAttribute16() {
		return $this->attribute16;
	}

	/**
	 * Get attribute 17
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 17
	 */
	public function getAttribute17() {
		return $this->attribute17;
	}

	/**
	 * Get attribute 18
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 18
	 */
	public function getAttribute18() {
		return $this->attribute18;
	}

	/**
	 * Get attribute 19
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 19
	 */
	public function getAttribute19() {
		return $this->attribute19;
	}

	/**
	 * Get attribute 20
	 *
	 * @deprecated in the NetX API
	 * @return string attribute 29
	 */
	public function getAttribute20() {
		return $this->attribute20;
	}

	/**
	 * Get attribute IDs
	 *
	 * @return array attribute IDs
	 */
	public function getAttributeIds() {
		return $this->attributeIds;
	}

	/**
	 * Get attribute names
	 *
	 * @return array attribute names
	 */
	public function getAttributeNames() {
		return $this->attributeNames;
	}

	/**
	 * Get attribute values
	 *
	 * @return array attribute values
	 */
	public function getAttributeValues() {
		return $this->attributeValues;
	}

	/**
	 * Get categories
	 *
	 * @return array categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Get category ID
	 *
	 * @return int category id
	 */
	public function getCategoryid() {
		return $this->categoryid;
	}

	/**
	 * Get create date
	 *
	 * @return string create date
	 */
	public function getCreationdate() {
		return $this->creationdate;
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
	 * Get description
	 *
	 * @return string description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Get file
	 *
	 * @return string file
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Get fileSize2
	 *
	 * @return long fileSize2
	 */
	public function getFileSize2() {
		return $this->fileSize2;
	}

	/**
	 * Get image height
	 *
	 * @return int image height
	 */
	public function getFileheight() {
		return $this->fileheight;
	}

	/**
	 * Get image preview height
	 *
	 * @return int image preview height
	 */
	public function getPreviewfileheight() {
		if($this->fileheight > $this->filewidth) {
			return 500;
		}else if($this->fileheight < $this->filewidth){
			return round($this->fileheight * (500 / $this->filewidth));
		}else{
			return 500;
		}
	}

	/**
	 * Get file size
	 *
	 * @return int file size
	 */
	public function getFilesize() {
		return $this->filesize;
	}

	/**
	 * Get file type
	 *
	 * @return int file type
	 */
	public function getFiletype() {
		return $this->filetype;
	}

	/**
	 * Get file type label
	 *
	 * @return string file type label
	 */
	public function getFiletypelabel() {
		return $this->filetypelabel;
	}

	/**
	 * Get image width
	 *
	 * @return int image width
	 */
	public function getFilewidth() {
		return $this->filewidth;
	}

	/**
	 * Get image width
	 *
	 * @return int image width
	 */
	public function getPreviewfilewidth() {
		if($this->filewidth > $this->fileheight) {
			return 500;
		}else if($this->filewidth < $this->fileheight){
			return round($this->filewidth * (500 / $this->fileheight));
		}else{
			return 500;
		}
	}

	/**
	 * Get location context
	 *
	 * @return string location context
	 */
	public function getLocationContext() {
		return $this->locationContext;
	}

	/**
	 * Get location ID
	 *
	 * @return int location ID
	 */
	public function getLocationid() {
		return $this->locationid;
	}

	/**
	 * Get mod date
	 *
	 * @return string mod date
	 */
	public function getModdate() {
		return $this->moddate;
	}

	/**
	 * Get modification date as Unix timestamp
	 *
	 * @return int modification timestamp
	 */
	public function getModDateTS() {
		return $this->modDateTS;
	}

	/**
	 * Get formatted modification date
	 *
	 * Passes the format string directly to PHP's date() function, so
	 * any formatting valid for the date function can be given here.
	 *
	 * @link http://www.php.net/manual/en/function.date.php
	 * @param string $dateFormat date format string
	 * @return string modification date
	 */
	public function getModDateFormatted($dateFormat = 'c') {
		return $this->getFormattedDate($this->modDateTS, $dateFormat);
	}

	/**
	 * Get moduserid
	 *
	 * @return int moduserid
	 */
	public function getModuserid() {
		return $this->moduserid;
	}

	/**
	 * Get moduserlabel
	 *
	 * @return string moduserlabel
	 */
	public function getModuserlabel() {
		return $this->moduserlabel;
	}

	/**
	 * Get name
	 *
	 * @return string name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get path
	 *
	 * @return string path
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Get permission matrix
	 *
	 * @return array permission matrix
	 */
	public function getPermissionMatrix() {
		return $this->permissionMatrix;
	}

	/**
	 * Get preview URI
	 *
	 * @return string preview URI
	 */
	public function getPreviewUrl() {
		return $this->previewUrl;
	}

	/**
	 * Get repurposeAvailability
	 *
	 * @return int repurposeAvailability
	 */
	public function getRepurposeAvailability() {
		return $this->repurposeAvailability;
	}

	/**
	 * Get status
	 *
	 * @return int status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Get thumb
	 *
	 * @return string thumb
	 */
	public function getThumb() {
		return $this->thumb;
	}

	/**
	 * Get thumb URI
	 *
	 * @return string thumb URI
	 */
	public function getThumbUrl() {
		return $this->thumbUrl;
	}

	/**
	 * Get zoom available
	 *
	 * @return boolean zoom available
	 */
	public function isZoomAvailable() {
		return $this->zoomAvailable;
	}

	/**
	 * Get view ID array
	 *
	 * @return array view ID array
	 */
	public function getViewIds() {
		return $this->viewIds;
	}

	/**
	 * Get view names array
	 *
	 * @return array view names array
	 */
	public function getViewNames() {
		return $this->viewNames;
	}

	/**
	 * Get constituent ID array
	 *
	 * @return array constituent ID array
	 */
	public function getConstituentIds() {
		return $this->constituentIds;
	}

	/**
	 * Get constituent type
	 *
	 * @return array constituent type
	 */
	public function getConstituentType() {
		return $this->constituentType;
	}

	/**
	 * Get number of pages
	 *
	 * @return int number of pages
	 */
	public function getNumPages() {
		if ($this->constituentType == 'page') {
			return count($this->constituentIds);
		} else {
			return 0;
		}
	}

	/**
	 * Returns true if there are pages for this asset
	 *
	 * @return boolean true if there are pages, false otherwise
	 */
	public function hasPages() {
		if (($this->constituentType == 'page') && (count($this->constituentIds) > 0)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get number of keyframes
	 *
	 * @return int number of keyframes
	 */
	public function getNumKeyframes() {
		if ($this->constituentType == 'keyframe') {
			return count($this->constituentIds);
		} else {
			return 0;
		}
	}

	/**
	 * Returns true if there are keyframes for this asset
	 *
	 * @return boolean true if there are keyframes, false otherwise
	 */
	public function hasKeyframes() {
		if (($this->constituentType == 'keyframe') && (count($this->constituentIds) > 0)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns string representation of array
	 *
	 * @param array $ar array to format
	 * @return string String representation of the array
	 */
	private function arrayFormatter($ar) {
		$ch = '[';
		$first = true;
		foreach ($ar as $idx => $val) {
			if (!$first) {
				$ch .= ', ';
			} else {
				$first = false;
			}
			$ch .= $val;
		}
		$ch .= ']';
		return $ch;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxAsset Object                                |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '              Asset ID: ' . $this->assetId . "\n";
		$ostr .= '           Attribute 1: ' . $this->attribute1 . "\n";
		$ostr .= '           Attribute 2: ' . $this->attribute2 . "\n";
		$ostr .= '           Attribute 3: ' . $this->attribute3 . "\n";
		$ostr .= '           Attribute 4: ' . $this->attribute4 . "\n";
		$ostr .= '           Attribute 5: ' . $this->attribute5 . "\n";
		$ostr .= '           Attribute 6: ' . $this->attribute6 . "\n";
		$ostr .= '           Attribute 7: ' . $this->attribute7 . "\n";
		$ostr .= '           Attribute 8: ' . $this->attribute8 . "\n";
		$ostr .= '           Attribute 9: ' . $this->attribute9 . "\n";
		$ostr .= '          Attribute 10: ' . $this->attribute10 . "\n";
		$ostr .= '          Attribute 11: ' . $this->attribute11 . "\n";
		$ostr .= '          Attribute 12: ' . $this->attribute12 . "\n";
		$ostr .= '          Attribute 13: ' . $this->attribute13 . "\n";
		$ostr .= '          Attribute 14: ' . $this->attribute14 . "\n";
		$ostr .= '          Attribute 15: ' . $this->attribute15 . "\n";
		$ostr .= '          Attribute 16: ' . $this->attribute16 . "\n";
		$ostr .= '          Attribute 17: ' . $this->attribute17 . "\n";
		$ostr .= '          Attribute 18: ' . $this->attribute18 . "\n";
		$ostr .= '          Attribute 19: ' . $this->attribute19 . "\n";
		$ostr .= '          Attribute 20: ' . $this->attribute20 . "\n";
		$ostr .= '         Attribute IDs: ' . $this->arrayFormatter($this->attributeIds) . "\n";
		$ostr .= '       Attribute Names: ' . $this->arrayFormatter($this->attributeNames) . "\n";
		$ostr .= '      Attribute Values: ' . $this->arrayFormatter($this->attributeValues) . "\n";
		$ostr .= '            Categories: ' . $this->arrayFormatter($this->categories) . "\n";
		$ostr .= '           Category ID: ' . $this->categoryid . "\n";
		$ostr .= '         Creation Date: ' . $this->creationdate . "\n";
		$ostr .= '           Description: ' . $this->description . "\n";
		$ostr .= '                  File: ' . $this->file . "\n";
		$ostr .= '           File Size 2: ' . $this->fileSize2 . "\n";
		$ostr .= '          Image Height: ' . $this->fileheight . "\n";
		$ostr .= '             File Size: ' . $this->filesize . "\n";
		$ostr .= '             File Type: ' . $this->filetype . "\n";
		$ostr .= '       File Type Label: ' . $this->filetypelabel . "\n";
		$ostr .= '           Image Width: ' . $this->filewidth . "\n";
		$ostr .= '      Location Context: ' . $this->locationContext . "\n";
		$ostr .= '           Location ID: ' . $this->locationid . "\n";
		$ostr .= '              Mod Date: ' . $this->moddate . "\n";
		$ostr .= '           Mod User ID: ' . $this->moduserid . "\n";
		$ostr .= '        Mod User Label: ' . $this->moduserlabel . "\n";
		$ostr .= '                  Name: ' . $this->name . "\n";
		$ostr .= '                  Path: ' . $this->path . "\n";
		$ostr .= '     Permission Matrix: ' . $this->arrayFormatter($this->permissionMatrix) . "\n";
		$ostr .= '           Preview URI: ' . $this->previewUrl . "\n";
		$ostr .= 'Repurpose Availability: ' . $this->repurposeAvailability . "\n";
		$ostr .= '                Status: ' . $this->status . "\n";
		$ostr .= '                 Thumb: ' . $this->thumb . "\n";
		$ostr .= '             Thumb URI: ' . $this->thumbUrl . "\n";
		$ostr .= '        Zoom Available: ' . (($this->zoomAvailable) ? 'true' : 'false') . "\n";
		$ostr .= '              View IDs: ' . $this->arrayFormatter($this->viewIds) . "\n";
		$ostr .= '            View Names: ' . $this->arrayFormatter($this->viewNames) . "\n";
		$ostr .= '      Constituent Type: ' . $this->constituentType . "\n";
		$ostr .= '       Constituent IDs: ' . $this->arrayFormatter($this->constituentIds) . "\n";
		$ostr .= '             Has Pages: ' . ($this->hasPages() ? 'true' : 'false') . "\n";
		$ostr .= '            Page Count: ' . $this->getNumPages() . "\n";
		$ostr .= '         Has Keyframes: ' . ($this->hasKeyframes() ? 'true' : 'false') . "\n";
		$ostr .= '        Keyframe Count: ' . $this->getNumKeyframes() . "\n";

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

		$out['attribute1'] = $this->attribute1;
		$out['attribute2'] = $this->attribute2;
		$out['attribute3'] = $this->attribute3;
		$out['attribute4'] = $this->attribute4;
		$out['attribute5'] = $this->attribute5;
		$out['attribute6'] = $this->attribute6;
		$out['attribute7'] = $this->attribute7;
		$out['attribute8'] = $this->attribute8;
		$out['attribute9'] = $this->attribute9;
		$out['attribute10'] = $this->attribute10;
		$out['attribute11'] = $this->attribute11;
		$out['attribute12'] = $this->attribute12;
		$out['attribute13'] = $this->attribute13;
		$out['attribute14'] = $this->attribute14;
		$out['attribute15'] = $this->attribute15;
		$out['attribute16'] = $this->attribute16;
		$out['attribute17'] = $this->attribute17;
		$out['attribute18'] = $this->attribute18;
		$out['attribute19'] = $this->attribute19;
		$out['attribute20'] = $this->attribute20;
		$out['categoryid'] = $this->categoryid;
		$out['creationdate'] = $this->creationdate;
		$out['description'] = $this->description;
		$out['name'] = $this->name;
		$out['path'] = $this->path;
		$out['previewUrl'] = $this->previewUrl;
		$out['repurposeAvailability'] = $this->repurposeAvailability;
		$out['status'] = $this->status;
		$out['thumb'] = $this->thumb;
		$out['thumbUrl'] = $this->thumbUrl;
		$out['zoomAvailable'] = (($this->zoomAvailable) ? 'true' : 'false');
		$out['constituentType'] = $this->constituentType;
		$out['hasPages'] = ($this->hasPages() ? 'true' : 'false');
		$out['hasKeyframes'] = ($this->hasKeyframes() ? 'true' : 'false');
		$out['numKeyframes'] = $this->getNumKeyframes();
		$out['numPages'] = $this->getNumPages();
		$out['file'] = $this->file;
		$out['fileSize2'] = $this->fileSize2;
		$out['filesize'] = $this->filesize;
		$out['fileheight'] = $this->fileheight;
		$out['filetype'] = $this->filetype;
		$out['filetypelabel'] = $this->filetypelabel;
		$out['filewidth'] = $this->filewidth;
		$out['locationContext'] = $this->locationContext;
		$out['locationid'] = $this->locationid;
		$out['moddate'] = $this->moddate;
		$out['moduserid'] = $this->moduserid;
		$out['moduserlabel'] = $this->moduserlabel;
		$out['attributeIds'] = $this->attributeIds;
		$out['attributeNames'] = $this->attributeNames;
		$out['attributeValues'] = $this->attributeValues;
		$out['categories'] = $this->categories;
		$out['permissionMatrix'] = $this->permissionMatrix;
		$out['viewIds'] = $this->viewIds;
		$out['viewNames'] = $this->viewNames;
		$out['constituentIds'] = $this->constituentIds;

		return $out;
	}
}

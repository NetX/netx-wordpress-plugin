<?php
/**
 * netxAssetList class file
 *
 * Contains definition for the netxAssetList class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Corresponds to AssetListBean
 *
 * @package NetxRestAPI
 */
class netxAssetList extends netxBean  {
	/**
	 * Asset ID
	 * @var int asset ID
	 * @access private
	 */
	private $assetId = 0;

	/**
	 * Label 1
	 * @var string label 1
	 * @access private
	 */
	private $label1 = '';

	/**
	 * Label 2
	 * @var string label 2
	 * @access private
	 */
	private $label2 = '';

	/**
	 * Label 3
	 * @var string label 3
	 * @access private
	 */
	private $label3 = '';

	/**
	 * Label 4
	 * @var string label 4
	 * @access private
	 */
	private $label4 = '';

	/**
	 * Label 5
	 * @var string label 5
	 * @access private
	 */
	private $label5 = '';

	/**
	 * Preview
	 * @var string preview
	 * @access private
	 */
	private $preview = '';

	/**
	 * Thumbnail URL
	 * @var string thumbnail URL
	 * @access private
	 */
	private $thumb = '';

	/**
	 * Constructor
	 * @return netxAssetList
	 */
	public function __construct() {
	}

	/**
	 * Set asset ID
	 *
	 * @param int $assetId asset ID
	 */
	public function setAssetID($assetId) {
		$this->assetId = $assetId;
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
	 * Set label 1
	 *
	 * @param string $label1 label 1
	 */
	public function setLabel1($label1) {
		$this->label1 = $label1;
	}

	/**
	 * Get label 1
	 *
	 * @return string label 1
	 */
	public function getLabel1() {
		return $this->label1;
	}

	/**
	 * Set label 2
	 *
	 * @param string $label2 label 2
	 */
	public function setLabel2($label2) {
		$this->label2 = $label2;
	}

	/**
	 * Get label 2
	 *
	 * @return string label 2
	 */
	public function getLabel2() {
		return $this->label2;
	}

	/**
	 * Set label 3
	 *
	 * @param string $label3 label 3
	 */
	public function setLabel3($label3) {
		$this->label3 = $label3;
	}

	/**
	 * Get label 3
	 *
	 * @return string label 3
	 */
	public function getLabel3() {
		return $this->label3;
	}

	/**
	 * Set label 4
	 *
	 * @param string $label4 label 4
	 */
	public function setLabel4($label4) {
		$this->label4 = $label4;
	}

	/**
	 * Get label 4
	 *
	 * @return string label 4
	 */
	public function getLabel4() {
		return $this->label4;
	}

	/**
	 * Set label 5
	 *
	 * @param string $label5 label 5
	 */
	public function setLabel5($label5) {
		$this->label5 = $label5;
	}

	/**
	 * Get label 5
	 *
	 * @return string label 5
	 */
	public function getLabel5() {
		return $this->label5;
	}

	/**
	 * Set preview
	 *
	 * @param string $preview preview
	 */
	public function setPreview($preview) {
		$this->preview = $preview;
	}

	/**
	 * Get preview
	 *
	 * @return string preview
	 */
	public function getPreview() {
		return $this->preview;
	}

	/**
	 * Set thumbnail URL
	 *
	 * @param string $thumb thumbnail URL
	 */
	public function setThumb($thumb) {
		$this->thumb = $thumb;
	}

	/**
	 * Get thumbnail URL
	 *
	 * @return string thumbnail URL
	 */
	public function getThumb() {
		return $this->thumb;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr  = 'Asset ID: ' . $this->assetId . "\n";
		$ostr .= ' Label 1: ' . $this->label1 . "\n";
		$ostr .= ' Label 2: ' . $this->label2 . "\n";
		$ostr .= ' Label 3: ' . $this->label3 . "\n";
		$ostr .= ' Label 4: ' . $this->label4 . "\n";
		$ostr .= ' Label 5: ' . $this->label5 . "\n";
		$ostr .= ' Preview: ' . $this->preview . "\n";
		$ostr .= '   Thumb: ' . $this->thumb . "\n";
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

		$out['assetId'] = $this->assetId;
		$out['label1'] = $this->label1;
		$out['label2'] = $this->label2;
		$out['label3'] = $this->label3;
		$out['label4'] = $this->label4;
		$out['label5'] = $this->label5;
		$out['preview'] = $this->preview;
		$out['thumb'] = $this->thumb;

		return $out;
	}
}

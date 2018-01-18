<?php
/**
 * netxUser class file
 *
 * Contains definition for the netxUser class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Corresponds to UserBean
 *
 * @package NetxRestAPI
 */
class netxUser extends netxBean {
	const TYPE_UNKNOWN = 0;
	const TYPE_PRODUCER = 4;
	const TYPE_MANAGER = 7;
	const TYPE_DIRECTOR = 8;
	const TYPE_IMPORTER = 3;
	const TYPE_CONSUMER = 2;
	const TYPE_BROWSER = 1;
	const TYPE_ADMINISTRATOR = 9;

	/**
	 * Address line 1
	 * @var string address line 1
	 * @access private
	 */
	private $address1 = '';

	/**
	 * Address line 2
	 * @var string address line 2
	 * @access private
	 */
	private $address2 = '';

	/**
	 * City
	 * @var string city
	 * @access private
	 */
	private $city = '';

	/**
	 * Country
	 * @var string Country
	 * @access private
	 */
	private $country = '';

	/**
	 * Email address
	 * @var string email address
	 * @access private
	 */
	private $email = '';

	/**
	 * Username
	 * @var string Username
	 * @access private
	 */
	private $login = '';

	/**
	 * Name 1
	 * @var string name 1
	 * @access private
	 */
	private $name1 = '';

	/**
	 * Name 2
	 * @var string name 2
	 * @access private
	 */
	private $name2 = '';

	/**
	 * Name 3
	 * @var string name 3
	 * @access private
	 */
	private $name3 = '';

	/**
	 * Name 4
	 * @var string name 4
	 * @access private
	 */
	private $name4 = '';

	/**
	 * Organization name
	 * @var string Organization name
	 * @access private
	 */
	private $organization = '';

	/**
	 * Phone 1
	 * @var string Phone 1
	 * @access private
	 */
	private $phone1 = '';

	/**
	 * Phone 2
	 * @var string Phone 2
	 * @access private
	 */
	private $phone2 = '';

	/**
	 * Phone 3
	 * @var string Phone 3
	 * @access private
	 */
	private $phone3 = '';

	/**
	 * Phone 4
	 * @var string Phone 4
	 * @access private
	 */
	private $phone4 = '';

	/**
	 * State
	 * @var string State
	 * @access private
	 */
	private $state = '';

	/**
	 * User type
	 * @var int User type
	 * @access private
	 */
	private $type = netxUser::TYPE_UNKNOWN;

	/**
	 * User ID
	 * @var int User ID
	 * @access private
	 */
	private $userid = 0;

	/**
	 * Postal code
	 * @var string Postal code
	 * @access private
	 */
	private $zip = '';

	/**
	 * Constructor
	 *
	 * @return netxUser
	 */
	public function __construct() {
	}

	/**
	 * Set address line 1
	 *
	 * @param string $address1 Address line 1
	 */
	public function setAddress1($address1) {
		$this->address1 = $address1;
	}

	/**
	 * Get address line 1
	 *
	 * @return string Address line 1
	 */
	public function getAddress1() {
		return $this->address1;
	}

	/**
	 * Set address line 2
	 *
	 * @param string $address2 Address line 2
	 */
	public function setAddress2($address2) {
		$this->address2 = $address2;
	}

	/**
	 * Get address line 2
	 *
	 * @return string Address line 2
	 */
	public function getAddress2() {
		return $this->address2;
	}

	/**
	 * Set city
	 *
	 * @param string $city city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * Get city
	 *
	 * @return string city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Set country
	 *
	 * @param string $country Country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * Get country
	 *
	 * @return string Country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Set email address
	 *
	 * @param string $email email address
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Get email address
	 *
	 * @return string email address
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set username
	 *
	 * @param string $login username
	 */
	public function setLogin($login) {
		$this->login = $login;
	}

	/**
	 * Get username
	 *
	 * @return string username
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * Set name 1
	 *
	 * @param string $name1 name 1
	 */
	public function setName1($name1) {
		$this->name1 = $name1;
	}

	/**
	 * Get name 1
	 *
	 * @return string name 1
	 */
	public function getName1() {
		return $this->name1;
	}

	/**
	 * Set name 2
	 *
	 * @param string $name2 name 2
	 */
	public function setName2($name2) {
		$this->name2 = $name2;
	}

	/**
	 * Get name 2
	 *
	 * @return string name 2
	 */
	public function getName2() {
		return $this->name2;
	}

	/**
	 * Set name 3
	 *
	 * @param string $name3 name 3
	 */
	public function setName3($name3) {
		$this->name3 = $name3;
	}

	/**
	 * Get name 3
	 *
	 * @return string name 3
	 */
	public function getName3() {
		return $this->name3;
	}

	/**
	 * Set name 4
	 *
	 * @param string $name4 name 4
	 */
	public function setName4($name4) {
		$this->name4 = $name4;
	}

	/**
	 * Get name 4
	 *
	 * @return string name 4
	 */
	public function getName4() {
		return $this->name4;
	}

	/**
	 * Set organization name
	 *
	 * @param string $organization organization name
	 */
	public function setOrganization($organization) {
		$this->organization = $organization;
	}

	/**
	 * Get organization name
	 *
	 * @return string organization name
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * Set phone 1
	 *
	 * @param string $phone1 phone 1
	 */
	public function setPhone1($phone1) {
		$this->phone1 = $phone1;
	}

	/**
	 * Get phone 1
	 *
	 * @return string phone 1
	 */
	public function getPhone1() {
		return $this->phone1;
	}

	/**
	 * Set phone 2
	 *
	 * @param string $phone2 phone 2
	 */
	public function setPhone2($phone2) {
		$this->phone2 = $phone2;
	}

	/**
	 * Get phone 2
	 *
	 * @return string phone 2
	 */
	public function getPhone2() {
		return $this->phone2;
	}

	/**
	 * Set phone 3
	 *
	 * @param string $phone3 phone 3
	 */
	public function setPhone3($phone3) {
		$this->phone3 = $phone3;
	}

	/**
	 * Get phone 3
	 *
	 * @return string phone 3
	 */
	public function getPhone3() {
		return $this->phone3;
	}

	/**
	 * Set phone 4
	 *
	 * @param string $phone4 phone 4
	 */
	public function setPhone4($phone4) {
		$this->phone4 = $phone4;
	}

	/**
	 * Get phone 4
	 *
	 * @return string phone 4
	 */
	public function getPhone4() {
		return $this->phone4;
	}

	/**
	 * Set state
	 *
	 * @param string $state state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * Get state
	 *
	 * @return string state
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * Set user type
	 *
	 * @param int $type user type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get user type
	 *
	 * @return int user type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get user type name
	 *
	 * @return string user type name
	 */
	public function getTypeName() {
		switch ($this->type) {
			case netxUser::TYPE_UNKNOWN:
				$typeName = 'Unknown';
				break;
			case netxUser::TYPE_BROWSER:
				$typeName = 'Browser';
				break;
			case netxUser::TYPE_CONSUMER:
				$typeName = 'Consumer';
				break;
			case netxUser::TYPE_IMPORTER:
				$typeName = 'Importer';
				break;
			case netxUser::TYPE_PRODUCER:
				$typeName = 'Producer';
				break;
			case netxUser::TYPE_MANAGER:
				$typeName = 'Manager';
				break;
			case netxUser::TYPE_DIRECTOR:
				$typeName = 'Director';
				break;
			case netxUser::TYPE_ADMINISTRATOR:
				$typeName = 'Administrator';
				break;
			default:
				$typeName = 'Unknown';
				break;
		}

		return $typeName;
	}

	/**
	 * Set user ID
	 *
	 * @param int $userid user ID
	 */
	public function setUserID($userid) {
		$this->userid = $userid;
	}

	/**
	 * Get user ID
	 *
	 * @return int user ID
	 */
	public function getUserID() {
		return $this->userid;
	}

	/**
	 * Set postal code
	 *
	 * @param string $zipcode postal code
	 */
	public function setZip($zipcode) {
		$this->zip = $zipcode;
	}

	/**
	 * Get postal code
	 *
	 * @return string postal code
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxUser Object                                 |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '       Login: ' . $this->login . "\n";
		$ostr .= '        Type: ' . $this->type . ' (' . $this->getTypeName() . ')' . "\n";
		$ostr .= '     User ID: ' . $this->userid . "\n";
		$ostr .= '       Email: ' . $this->email . "\n";
		$ostr .= '      Name 1: ' . $this->name1 . "\n";
		$ostr .= '      Name 2: ' . $this->name2 . "\n";
		$ostr .= '      Name 3: ' . $this->name3 . "\n";
		$ostr .= '      Name 4: ' . $this->name4 . "\n";
		$ostr .= 'Organization: ' . $this->organization . "\n";
		$ostr .= '   Address 1: ' . $this->address1 . "\n";
		$ostr .= '   Address 2: ' . $this->address2 . "\n";
		$ostr .= '        City: ' . $this->city . "\n";
		$ostr .= '       State: ' . $this->state . "\n";
		$ostr .= '         Zip: ' . $this->zip . "\n";
		$ostr .= '     Country: ' . $this->country . "\n";
		$ostr .= '     Phone 1: ' . $this->phone1 . "\n";
		$ostr .= '     Phone 2: ' . $this->phone2 . "\n";
		$ostr .= '     Phone 3: ' . $this->phone3 . "\n";
		$ostr .= '     Phone 4: ' . $this->phone4 . "\n";
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

		$out['login'] = $this->login;
		$out['type'] = $this->type;
		$out['typename'] = $this->getTypeName();
		$out['userid'] = $this->userid;
		$out['email'] = $this->email;
		$out['name1'] = $this->name1;
		$out['name2'] = $this->name2;
		$out['name3'] = $this->name3;
		$out['name4'] = $this->name4;
		$out['organization'] = $this->organization;
		$out['address1'] = $this->address1;
		$out['address2'] = $this->address2;
		$out['city'] = $this->city;
		$out['state'] = $this->state;
		$out['zip'] = $this->zip;
		$out['country'] = $this->country;
		$out['phone1'] = $this->phone1;
		$out['phone2'] = $this->phone2;
		$out['phone3'] = $this->phone3;
		$out['phone4'] = $this->phone4;

		return $out;
	}
}

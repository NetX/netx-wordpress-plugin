<?php
/**
 * @package NetxWPPlugin
 */

/**
 * NetX REST API Library wrapper class
 */
require_once(dirname(__FILE__) . '/includes/netxRestWrapper.php');

/**
 * Wordpress plugin admin functionality
 *
 * @package NetxWPPlugin
 */
class netxMediaManagerPluginAdmin {
	public static function init() {
		new netxMediaManagerPluginAdmin();
	}

	/**
	 * Add sub page to the Settings Menu
	 */
	public function __construct() {
		add_options_page('NetX Settings', 'NetX', 'administrator', __FILE__, array($this, 'settingsPage'));
		add_action('admin_init', array($this, 'registerSettings'));
	}

	/**
	 * Register plugin settings
	 */
	public function registerSettings() {
 		register_setting('netx_options', 'netx_options', array($this, 'netxValidateOptions'));

		add_settings_section('main_section', 'DAM Settings', array($this, 'mainSection'), __FILE__);

		add_settings_field('netx_username', 'NetX Username', array($this, 'netxUsername'), __FILE__, 'main_section');
		add_settings_field('netx_password', 'NetX Password', array($this, 'netxPassword'), __FILE__, 'main_section');
		add_settings_field('netx_uri', 'NetX URI', array($this, 'netxURI'), __FILE__, 'main_section');
		add_settings_field('netx_paging_size', 'NetX Media Page Size', array($this, 'netxPageSize'), __FILE__, 'main_section');

		
		add_settings_field('netx_base_category_id', 'NetX Base Category', array($this, 'netxCategory'), __FILE__, 'main_section');
		add_settings_field('netx_base_category_path', '', array($this, 'netxCategoryPath'), __FILE__, 'main_section');

		add_settings_field('netx_access_token', 'NetX API Token', array($this, 'netxApiToken'), __FILE__, 'main_section');
	}

	/**
	 * Settings main section callback
	 */
	public function mainSection() {
		echo '<hr />';
	}

	/**
	 * Create NetX username field
	 */
	public function netxUsername() {
		$options = get_option('netx_options');
		echo '<input type="text" name="netx_options[netx_username]" value="' . $options['netx_username'] . '" />';
	}

	/**
	 * Create NetX password field
	 */
	public function netxPassword() {
		$options = get_option('netx_options');
		echo '<input type="password" name="netx_options[netx_password]" value="' . $options['netx_password'] . '" />';
	}

	public function netxApiToken() {
		$options = get_option('netx_options');
		echo '<input type="text" name="netx_options[netx_access_token]" value="' . $options['netx_access_token'] . '" />';
	}

	/**
	 * Create NetX URI field
	 */
	public function netxURI() {
		$options = get_option('netx_options');
		echo '<p>NetX Uri should not include protocol or any trailing path. The uri should only include domain name. Example: "<i>subdomain.netx.net</i>"</p>';
		echo '<input type="text" name="netx_options[netx_uri]" value="' . $options['netx_uri'] . '" />';
	}

	/**
	 * Create NetX Page Size field
	 */
	public function netxPageSize() {
		$options = get_option('netx_options');
		echo '<input type="number" name="netx_options[netx_paging_size]" value="' . $options['netx_paging_size'] . '" />';
	}

	/**
	 * Create NetX Category dropdown
	 */
	public function netxCategory() {
		$options = get_option('netx_options');
		if (
			(strlen($options['netx_username']) > 0) &&
			(strlen($options['netx_password']) > 0) &&
			(strlen($options['netx_uri']) > 0)) {

			try {
				$netx = new netxRestWrapper();
				$catList = $netx->getCategoryPathList();
				echo '<select name="netx_options[netx_base_category_id]">';

				foreach ($catList as $catPath => $catID) {
					$optBaseID = $options['netx_base_category_id'];
					$catSelected = (($optBaseID == $catID) ? ' selected="selected"' : '');
					echo '<option value="' . $catID . '"' . $catSelected . '>' . $catPath . '</option>';
				}

				echo '</select>';
			} catch(Exception $e) {
				echo('<p style="color: red;"><b>Could not connect to NetX. Check username, password, and uri.</b></p>');
			}
		}
	}

	/**
	 *	Create NetX Category Path hidden field
	 */
	public function netxCategoryPath(){
		$options = get_option('netx_options');
		echo '<input type="hidden" name="netx_options[netx_base_category_path]" value="' . $options['netx_base_category_path'] . '" />';
	}

	/**
	 * Validate options field input
	 */
	 public function netxValidateOptions($input) {
	 	try {
		 	$tmpURI = preg_replace('/^https?:\/+/i', '', $input['netx_uri']);
		 	$input['netx_uri'] = $tmpURI;

		 	if ($input['netx_base_category_id']) {
				$input['netx_base_category_path'] = self::getCategoryPath($input['netx_base_category_id']);
		 	}
	 	} catch (Exception $e) {
	 		error_log("netxValidateOptions: " . $e->getMessage());
	 	}

		return $input;
	}

	/**
	 * Settings page
	 */
	public function settingsPage() {
?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2>NetX Settings</h2>
			This is where you manage NetX settings for the media library
			<form action="options.php" method="post">
			<?php settings_fields('netx_options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
			</form>
		</div>
<?php
	}
	/**
	 * Runs when netx_base_category_id option is changed, so that we
	 * can look up the path for that category and save it in options
	 * as well.
	 */
	public function getCategoryPath($newCatID) {
		$netx = new netxRestWrapper();
		$catList = $netx->getCategoryPathList();
		$trans = array_flip($catList);
		$path = $trans[$newCatID];
		return $path;
	}
}

// Admin menu/options pages
add_action('admin_menu', array('netxMediaManagerPluginAdmin', 'init'));

?>

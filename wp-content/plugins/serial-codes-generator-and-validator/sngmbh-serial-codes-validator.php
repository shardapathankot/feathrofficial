<?php
/**
 * Plugin Name: Serial Codes Generator and Validator with WooCommerce Support
 * Plugin URI: https://vollstart.de/serial-codes-validator-premium/docs/
 * Description: You can create and generate serials and codes. Print them on your products boxes or manuals. Your customer can check if the code is valid on your website. The Premium allows you also to activate user registration and more. This allows your user to register them self to a serial number.
 * Version: 2.6.3
 * Author: Saso Nikolov
 * Author URI: https://vollstart.de
 * Text Domain: sngmbh-serial-codes-validator
 *
 * Serial Code Validator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// https://developer.wordpress.org/plugins/security/securing-output/
// https://developer.wordpress.org/plugins/security/securing-input/

include(plugin_dir_path(__FILE__)."init_file.php");

if (!defined('SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION'))
	define('SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION', '2.6.3');
if (!defined('SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_DIR_PATH'))
	define('SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

include_once plugin_dir_path(__FILE__)."SNGMBH.php";

class sngmbhSerialcodesValidator {
	private $_js_version;
	private $_js_file = 'sngmbh-serialcodes-validator.js';
	private $_js_nonce = 'sngmbhSerialcodesValidator';
	public $_do_action_prefix = 'sngmbh_serial-code_';
	public $_add_filter_prefix = 'sngmbh_serial-code_';
	protected $_prefix = 'sngmbhSerialcodesValidator';
	protected $_shortcode = 'sngmbhSerialcodesValidator';
	protected $_shortcode_mycode = 'sngmbhSerialcodesValidator_code';
	protected $_divId = 'sngmbhSerialcodesValidator';

	private $_isPrem = null;
	private $_premium_plugin_name = 'sngmbh-serial-codes-validator-premium';
	private $_premium_function_file = 'sngmbh-serialcodes-validator-functions.php';
	private $PREMFUNCTIONS = null;
	private $BASE = null;
	private $DB = null;
	private $CORE = null;
	private $ADMIN = null;
	private $FRONTEND = null;
	private $OPTIONS = null;
	private $WC = null;

	private $isAllowedAccess = null;

	public function __construct() {
		$this->_js_version = $this->getPluginVersion();
		//if (defined( 'WP_DEBUG')) $this->_js_version = time();
		add_action( $this->_prefix."_cronjob_daily", [$this, 'cronjob_daily'], 10, 0);
		add_action( 'upgrader_process_complete', [$this, 'my_upgrade_function'], 10, 2);
		add_action('admin_init', [$this, 'initialize_plugin']);
		if (is_admin()) { // called in backend admin, admin-ajax!
			$this->init_backend();
		} else { // called in front end
			$this->init_frontend();
		}
		add_action( 'plugins_loaded', [$this, 'WooCommercePluginLoaded'] );
  		if (basename($_SERVER['SCRIPT_NAME']) == "admin-ajax.php") {
			add_action('wp_ajax_nopriv_'.$this->_prefix.'_executeFrontend', [$this,'executeFrontend_a'], 10, 0); // nicht angemeldete user, sollen eine antwort erhalten
			add_action('wp_ajax_'.$this->_prefix.'_executeFrontend', [$this,'executeFrontend_a'], 10, 0); // falls eingeloggt ist
			add_action('wp_ajax_'.$this->_prefix.'_executeWCBackend', [$this,'executeWCBackend'], 10, 0); // falls eingeloggt ist
		}
		$this->sngmbhSerialcodesValidator_cronjob_daily_activate();
	}
	public function getPluginPath() {
		return SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_DIR_PATH;
	}
	public function getPluginVersion() {
		return SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION;
	}
	public function getPluginVersions() {
		$ret = ['basic'=>SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION, 'premium'=>'', 'debug'=>''];
		if (defined('SNGMBH_SERIALCODES_VALIDATOR_PREMIUM_PLUGIN_VERSION')) {
			$ret['premium'] = SNGMBH_SERIALCODES_VALIDATOR_PREMIUM_PLUGIN_VERSION;
		}
		if (defined('WP_DEBUG') && WP_DEBUG) {
			$ret['debug'] = '<span style="color:red;">is active</span>';
		}
		return $ret;
	}
	public function getDB() {
		return SNGMBH::getDB(plugin_dir_path(__FILE__), "sngmbhSerialcodesValidatorDB", $this);
	}
	public function getBase() {
		if ($this->BASE == null) {
			if (!class_exists('vollstart_Base')) {
				include_once plugin_dir_path(__FILE__)."vollstart_Base.php";
			}
			$this->BASE = new vollstart_Base($this);
		}
		return $this->BASE;
	}
	public function getCore() {
		if ($this->CORE == null) {
			if (!class_exists('sngmbhSerialcodesValidator_Core')) {
				include_once plugin_dir_path(__FILE__)."sngmbhSerialcodesValidator_Core.php";
			}
			$this->CORE = new sngmbhSerialcodesValidator_Core($this);
		}
		return $this->CORE;
	}
	public function getAdmin() {
		if ($this->ADMIN == null) {
			if (!class_exists('sngmbhSerialcodesValidator_AdminSettings')) {
				include_once plugin_dir_path(__FILE__)."sngmbhSerialcodesValidator_AdminSettings.php";
			}
			$this->ADMIN = new sngmbhSerialcodesValidator_AdminSettings($this);
		}
		return $this->ADMIN;
	}
	public function getFrontend() {
		if ($this->FRONTEND == null) {
			if (!class_exists('sngmbhSerialcodesValidator_Frontend')) {
				include_once plugin_dir_path(__FILE__)."sngmbhSerialcodesValidator_Frontend.php";
			}
			$this->FRONTEND = new sngmbhSerialcodesValidator_Frontend($this);
		}
		return $this->FRONTEND;
	}
	public function getOptions() {
		if ($this->OPTIONS == null) {
			if (!class_exists('sngmbhSerialcodesValidator_Options')) {
				include_once plugin_dir_path(__FILE__)."sngmbhSerialcodesValidator_Options.php";
			}
			$this->OPTIONS = new sngmbhSerialcodesValidator_Options($this, $this->_prefix);
			$this->OPTIONS->initOptions();
		}
		return $this->OPTIONS;
	}
	public function getWC() {
		if ($this->WC == null) {
			if (!class_exists('sngmbhSerialcodesValidator_WC')) {
				include_once dirname(__FILE__).'/woocommerce-hooks.php';
			}
			//$this->WC = new sngmbhSerialcodesValidator_WC($this);
			$this->WC = sngmbhSerialcodesValidator_WC::Instance($this);
		}
		return $this->WC;
	}
	public function getTicketHandler() {
		if (!class_exists('vollstart_Ticket')) {
			include_once plugin_dir_path(__FILE__)."vollstart_Ticket.php";
		}
		return vollstart_Ticket::Instance($_SERVER["REQUEST_URI"]);
	}
	public function getPremiumFunctions() {
		if ($this->_isPrem == null && $this->PREMFUNCTIONS == null) {
			$this->_isPrem = false;
			$premPluginFolder = $this->getPremiumPluginFolder();
			$file = $premPluginFolder.$this->_premium_function_file;
			$premiumFile = plugin_dir_path(__FILE__)."../".$file;
			if (file_exists($premiumFile)) { // check ob active ist nicht nötig, das das getPremiumPluginFolder schon macht
				if (!class_exists('sngmbhSerialcodesValidator_PremiumFunctions')) {
					include_once $premiumFile;
				}
				// TODO: nach 2.0.11 prem parameter anpassen
				$this->PREMFUNCTIONS = new sngmbhSerialcodesValidator_PremiumFunctions($this->getBase(), plugin_dir_path(__FILE__), $this->_prefix, $this->getDB());
				$this->_isPrem = $this->PREMFUNCTIONS->isPremium();
			}
		}
		return $this->PREMFUNCTIONS;
	}
	private function getPremiumPluginFolder() {
		$plugins = get_option('active_plugins', []);
		$premiumFile = "";
		foreach($plugins as $plugin) {
			if (strpos(" ".$plugin, $this->_premium_plugin_name) > 0) {
				$premiumFile = plugin_dir_path($plugin);
				break;
			}
		}
		return $premiumFile;
	}
	public function isPremium() {
		if ($this->_isPrem == null) $this->getPremiumFunctions();
		return $this->_isPrem;
	}
	public function getPrefix() {
		return $this->_prefix;
	}

	public function get_expiration() {
		$option_name = $this->getPrefix()."_premium_serial_expiration";
		$info = get_option( $option_name );
		$info_obj = ["last_run"=>0, "timestamp"=>0, "expiration_date"=>"", "timezone"=>""]; // expiration_date is only for display
		if (!empty($info)) {
			$info_obj = array_merge($info_obj, json_decode($info, true));
		}
		return $info_obj;
	}
	// execute the cronjob via add_action hook
	public function cronjob_daily() {
		$option_name = $this->getPrefix()."_premium_serial_expiration";
		// check the expiration of the premium serial
		if ($this->isPremium()) {
			$info_obj = $this->get_expiration();
			$doCheck = false;
			if ($info_obj["last_run"] == 0) {
				$doCheck = true;
			} else {
				if (isset($info_obj["timestamp"])) {
					if ($info_obj["timestamp"] >= 0) {
						$doCheck = true;
						if (strtotime("+21 days") > intval($info_obj["timestamp"])) {
							// check if enough time past after the last check
							if (strtotime("-7 days") < intval($info_obj["last_run"])) {
								$doCheck = false; // wait till the cache expires
							}
						}
					}
				} else {
					$doCheck = true;
				}
			}
			if ($doCheck) {
				$serial = trim(get_option( "sngmbh-serial-codes-validator-premium_serial" ));
				if (!empty($serial)) {
					$domain = parse_url( get_site_url(), PHP_URL_HOST );

					$url = "https://vollstart.de/plugins/serial-code-validator-premium/"
								.'?checking_for_updates=2&ver='.SNGMBH_SERIALCODES_VALIDATOR_PREMIUM_PLUGIN_VERSION
								."&m=".get_option('admin_email')
								."&d=".$domain
								."&serial=".urlencode($serial);

					$response = wp_remote_get($url, ['timeout' => 45]);
					if (is_wp_error($response)) {
					} else {
						$body = wp_remote_retrieve_body( $response );
						$data = json_decode( $body, true );
						if (isset($data["isCheckCall"]) && $data["isCheckCall"] == 1) {
							print_r($data);
							// store it get_option( self::$_dbprefix."db_version" ); update_option( self::$_dbprefix."db_version", $this->dbversion );
							$info_obj["last_run"] = time();
							$info_obj = array_merge($data, $info_obj);
							$value = $this->getCore()->json_encode_with_error_handling($info_obj);
							update_option($option_name, $value);
						}
					}
				}
			}
		}
	}
	// register the cronjob
	public function sngmbhSerialcodesValidator_cronjob_daily_activate() {
		$args = [];
		if (! wp_next_scheduled ( $this->_prefix.'_cronjob_daily', $args )) {
			// set the action hook name to to be called
			wp_schedule_event( strtotime("00:05"), 'daily', $this->_prefix.'_cronjob_daily', $args );
		}
	}
	// remove the cronjob
	public function sngmbhSerialcodesValidator_cronjob_daily_deactivate() {
		wp_clear_scheduled_hook( $this->_prefix.'_cronjob_daily' );
	}

	public function getMaxValues() {
		return ['storeip'=>false,'allowuserreg'=>false,'codes_total'=>500,'codes'=>500,'lists'=>5];
	}
	public function my_upgrade_function( $upgrader_object, $options ) {
		$this->getCore()->my_upgrade_function( $upgrader_object, $options );
	}
	/**
	* check for ticket detail page request
	*/
	private function wc_checkTicketDetailPage() {
		// /wp-content/plugins/serial-codes-generator-and-validator/ticket/
		$p = $this->getCore()->getTicketURLPath();
		$t = explode("/", $_SERVER["REQUEST_URI"]);
		if(substr($_SERVER["REQUEST_URI"], 0, strlen($p)) == $p && $t[count($t)-2] != "scanner") {
			$this->getTicketHandler()->initFilterAndActions();
		}
	}
	public function WooCommercePluginLoaded() {
		$this->getWC();
		// set routing -- NEEDS to be replaced by add_rewrite_rule later
		$this->wc_checkTicketDetailPage();
		// set all WC handler
			// sind noch alle in woocommerce-hooks.php
	}
	private function init_frontend() {
		add_shortcode($this->_shortcode, [$this, 'replacingShortcode']);
		add_shortcode($this->_shortcode_mycode, [$this, 'replacingShortcodeMyCode']);
	}
	private function init_backend() {
		add_action('admin_menu', [$this, 'register_options_page']);
		register_activation_hook(__FILE__, [$this, 'plugin_activated']);
		register_deactivation_hook( __FILE__, [$this, 'plugin_deactivated'] );
		//register_uninstall_hook( __FILE__, ['sngmbhSerialcodesValidatorDB::plugin_uninstall' ); // MUSS NOCH GETESTE WERDEN
		add_action( 'plugins_loaded', [$this, 'plugins_loaded'] );
		add_action( 'show_user_profile', [$this, 'show_user_profile'] );

		if (basename($_SERVER['SCRIPT_NAME']) == "admin-ajax.php") {
			add_action('wp_ajax_'.$this->_prefix.'_executeAdminSettings', [$this,'executeAdminSettings_a'], 10, 0);
		}
	}
	public function plugin_deactivated(){
		$this->sngmbhSerialcodesValidator_cronjob_daily_deactivate();
		sngmbhSerialcodesValidatorDB::plugin_deactivated();
	}
	public static function plugin_uninstall(){
    	sngmbhSerialcodesValidatorDB::plugin_uninstall();
		sngmbhSerialcodesValidator_AdminSettings::plugin_uninstall();
	}
	public function plugin_activated($is_network_wide=false) { // und auch für updates, macht es einfacher
		$this->getDB(); // um installiere Tabellen auszuführen
    	update_option('SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION', SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION);
		$this->getAdmin()->generateFirstCodeList();
		$this->sngmbhSerialcodesValidator_cronjob_daily_activate();
		do_action( $this->_do_action_prefix.'activated' );
	}
	public function plugins_loaded() {
		if (SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION !== get_option('SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_VERSION', '')) $this->plugin_activated(); // vermutlich wurde die aktivierung übersprungen, bei änderungen direkt an den files
	}
    public function initialize_plugin() {
		$this->getDB(); // um installiere Tabellen auszuführen
		do_action( $this->_do_action_prefix.'initialized' );
    }
	function show_user_profile($profileuser) {
		return $this->getAdmin()->show_user_profile($profileuser);
	}
	function register_options_page() {
	  	add_options_page('Serial Codes Validator', 'Serial Codes Validator', 'manage_options', 'sngmbh-serialcodes-validator', [$this,'options_page']);
	  	add_menu_page( 'Serial Codes Validator', 'Serial Codes Validator', 'manage_options', 'sngmbh-serialcodes-validator', [$this,'options_page'], '', null );
	}

	function options_page() {
		$allowed = $this->isUserAllowedToAccessAdminArea();
		if ( !current_user_can( 'manage_options' ) || !$allowed )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		// einbinden das js starter skript
		$js_url = $this->_js_file."?ver=".$this->_js_version;
		if (defined( 'WP_DEBUG')) $js_url .= '&debug=1';

		wp_enqueue_media(); // um die js wp.media lib zu laden
        wp_enqueue_script(
            'ajax_script',
            plugins_url( $js_url,__FILE__ ),
            array('jquery', 'jquery-ui-dialog')
        );
		$js_url = "jquery.qrcode.min.js?ver=".$this->_js_version;
		wp_enqueue_script(
			'ajax_script2',
			plugins_url( "3rd/".$js_url,__FILE__ ),
			array('jquery', 'jquery-ui-dialog')
		);

		wp_enqueue_style("wp-jquery-ui-dialog");

		// per script eine variable einbinden, die url hat den wp-admin prefix
		// damit im backend.js dann die richtige callback url genutzt werden kann
        wp_localize_script(
            'ajax_script',
            'Ajax_'.$this->_prefix, // name der injected variable
            array(
            	'_plugin_home_url' =>plugins_url( "",__FILE__ ),
            	'_action' => $this->_prefix.'_executeAdminSettings',
            	'_max'=>$this->getBase()->getMaxValues(),
            	'_isPremium'=>$this->isPremium(),
            	'_isUserLoggedin'=>is_user_logged_in(),
            	'_premJS'=>$this->isPremium() ? $this->getPremiumFunctions()->getJSBackendFile() : '',
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( $this->_js_nonce ),
                'ajaxActionPrefix' => $this->_prefix,
                'divPrefix' => $this->_prefix,
                'divId' => $this->_divId,
                'jsFiles' => plugins_url( 'backend.js?_v='.$this->_js_version,__FILE__ )
            )
        );

		$versions = $this->getPluginVersions();
		$versions_tail = $versions['basic'].($versions['premium'] != "" ? ', Premium: '.$versions['premium'] : '');
		if ($versions['debug'] != "") $versions_tail .= ', DEBUGMODE: '.$versions['debug'];
		?>
		<div>
			<h2>Serial Codes Validator <sup>Version: <?php echo $versions_tail; ?></sup></h2>
			<p>Shortcode to display the serial check for the user: <b>[<?php echo $this->_shortcode; ?>]</b>. <a href="#shortcodedetails">Learn here more about the possible parameters.</a></p>
			<p>If you like our plugin, then please give us a <a target="_blank" href="https://wordpress.org/support/plugin/serial-codes-generator-and-validator/reviews?rate=5#new-post">★★★★★ 5-Star Rating</a>.</p>
			<div style="clear:both;" data-id="plugin_info_area"></div>
			<div id="<?php echo esc_attr($this->_divId); ?>"></div>
			<div style="margin-top:100px;">
				<hr>
				<a name="shortcodedetails"></a>
				<h3>Plugin Rating</h3>
				<p>If you like our plugin, then please give us a <a target="_blank" href="https://wordpress.org/support/plugin/serial-codes-generator-and-validator/reviews?rate=5#new-post">★★★★★ 5-Star Rating</a>.</p>
				<h3>Shortcode parameter In- & Output</h3>
				<a href="https://vollstart.de/serial-codes-validator-premium/docs/" target="_blank">Click here for more help about the options</a>
				<p>You can use your own HTML input, output and trigger component. If you add the parameters (all 3 mandatory to use this feature), then the default input area will not be rendered.</p>
				<ul>
					<li><b>inputid</b><br>inputid="html-element-id". The value of this component will be taken. It need to be an HTML input element. We will access the value-parameter of it.</li>
					<li><b>triggerid</b><br>triggerid="html-element-id". The onclick event of this component will be replaced by our function to call the server validation with the code.</li>
					<li><b>outputid</b><br>outputid="html-element-id". The content of this component will be replaced by the server result after the check . We will use the innerHTML property of it, so use a DIV, SPAN, TD or similar for best results.</li>
				</ul>
				<h3>Shortcode parameter Javascript</h3>
				<p>You can add your Javascript function name. Both parameters are optional and not required. If functions will be called before the code is sent to the server or displaying the result.</p>
				<ul>
					<li><b>jspre</b><br>jspre="function-name". The function will be called. The input parameter will be the code. If your function returns a value, than this returned value will be used otherwise the entered code will be used.</li>
					<li><b>jsafter</b><br>jsafter="function-name". The function will be called. The input parameter will be the result JSON object from the server.</li>
				</ul>
				<h3>Shortcode to display the assigned serial codes of an user within a page</h3>
				<b>[<?php echo esc_html($this->_shortcode_mycode); ?>]</b>
				<h3>PHP Filters</h3>
				<p>You can use PHP code to register your filter functions for the validation check.
				<a href="https://vollstart.de/serial-codes-validator-premium/docs/#filters" target="_blank">Click here for more help about the functions</a>
				</p>
				<ul>
					<li>add_filter('<?php echo $this->_add_filter_prefix.'beforeCheckCodePre'; ?>', 'myfunc', 20, 1)</li>
					<li>add_filter('<?php echo $this->_add_filter_prefix.'beforeCheckCode'; ?>', 'myfunc', 20, 1)</li>
					<li>add_filter('<?php echo $this->_add_filter_prefix.'afterCheckCodePre'; ?>', 'myfunc', 20, 1)</li>
					<li>add_filter('<?php echo $this->_add_filter_prefix.'afterCheckCode'; ?>', 'myfunc', 20, 1)</li>
				</ul>
				<center><a target="_blank" href="https://vollstart.de">VOLLSTART</a></center>
			</div>
	  	</div>
		<?php
		do_action( $this->_do_action_prefix.'options_page' );
	}

	private function isUserAllowedToAccessAdminArea() {
		if ($this->isAllowedAccess != null) return $this->isAllowedAccess;
		if ($this->getOptions()->isOptionCheckboxActive('allowOnlySepcificRoleAccessToAdmin')) {
			// check welche rollen
			$user = wp_get_current_user();
			$user_roles = (array) $user->roles;
			if (in_array("administrator", $user_roles)) {
				$this->isAllowedAccess = true;
			} else {
				$adminAreaAllowedRoles = $this->getOptions()->getOptionValue('adminAreaAllowedRoles');
				foreach($adminAreaAllowedRoles as $role_name) {
					if (in_array($role_name, $user_roles)) {
						$this->isAllowedAccess = true;
						break;
					};
				}
			}
		} else {
			$this->isAllowedAccess = true;
		}
		return $this->isAllowedAccess;
	}

	public function executeAdminSettings_a() {
		if (!SNGMBH::issetRPara('a_sngmbh')) return wp_send_json_success("a_sngmbh not provided");
		return $this->executeAdminSettings(SNGMBH::getRequestPara('a_sngmbh')); // to prevent WP adds parameters
	}

	public function executeAdminSettings($a=0, $data=null) {
		if ($this->isUserAllowedToAccessAdminArea()) {
			if ($a === 0 && !SNGMBH::issetRPara('a_sngmbh')) return wp_send_json_success("a not provided");

			if ($data == null) {
				$data = SNGMBH::issetRPara('data') ? SNGMBH::getRequestPara('data') : [];
			}
			if ($a === 0 || empty($a) || trim($a) == "") {
				$a = SNGMBH::getRequestPara('a_sngmbh');
			}
			do_action( $this->_do_action_prefix.'executeAdminSettings', $a, $data );
			return $this->getAdmin()->executeJSON($a, $data);
		}
	}

	public function executeFrontend_a() {
		return $this->executeFrontend(); // to prevent WP adds parameters
	}

	public function executeWCBackend() {
		if (!SNGMBH::issetRPara('a_sngmbh')) return wp_send_json_success("a_sngmbh not provided");
		$data = SNGMBH::issetRPara('data') ? SNGMBH::getRequestPara('data') : [];
		return $this->getWC()->executeJSON(SNGMBH::getRequestPara('a_sngmbh'), $data);
	}

	public function executeFrontend($a=0, $data=null) {
		$sngmbhSerialcodesValidator_Frontend = $this->getFrontend();
		if ($a === 0 && !SNGMBH::issetRPara('a_sngmbh')) return wp_send_json_success("a not provided");

		if ($data == null) {
			$data = SNGMBH::issetRPara('data') ? SNGMBH::getRequestPara('data') : [];
		}
		if ($a === 0 || empty($a) || trim($a) == "") {
			$a = SNGMBH::getRequestPara('a_sngmbh');
		}
		do_action( $this->_do_action_prefix.'executeFrontend', $a, $data );
		return $sngmbhSerialcodesValidator_Frontend->executeJSON($a, $data);
	}

	public function replacingShortcode($attr=[], $content = null, $tag = '') {
		add_filter( $this->_add_filter_prefix.'replaceShortcode', [$this, 'replaceShortcode'], 10, 3 );
		$ret = apply_filters( $this->_add_filter_prefix.'replaceShortcode', $attr, $content, $tag );
		return $ret;
	}

	public function getMyCodeText($user_id) {
		$ret = '';
		// check ob eingeloggt
		$pre_text = $this->getOptions()->getOptionValue('userDisplayCodePrefix', '');
		//userDisplayCodePrefixAlways

		if ($user_id > 0) {
			// lade codes mit user_id
			$codes = $this->getCore()->getCodesByRegUserId($user_id);
			if (count($codes) > 0) {
				$myCodes = [];
				foreach($codes as $codeObj) {
					$_c = $codeObj['code_display'];
					if ($codeObj['aktiv'] == 1) {
						if ($this->getCore()->checkCodeExpired($codeObj)) {
							$_c .= ' EXPIRED';
						}
					} else if ($codeObj['aktiv'] == 0) {
						$_c .= ' DISABLED';
					} else if ($codeObj['aktiv'] == 2) {
						$_c .= ' REPORTED AS STOLEN';
					}
					$myCodes[] = $_c;
				}
				// ersetze text
				$ret .= $pre_text;
				$sep = $this->getOptions()->getOptionValue('userDisplayCodeSeperator', ', ');
				$ret .= implode($sep, $myCodes);
			}
		}
		if (empty($ret) && $this->getOptions()->isOptionCheckboxActive('userDisplayCodePrefixAlways')) {
			$ret .= $pre_text;
		}
		return $ret;
	}

	public function replacingShortcodeMyCode($attr=[], $content = null, $tag = '') {
		$user_id = get_current_user_id();
		return $this->getMyCodeText($user_id);
	}

	public function replaceShortcode($attr=[], $content = null, $tag = '') {
		// einbinden das js starter skript
		$js_url = $this->_js_file."?_v=".$this->_js_version;
		if (defined( 'WP_DEBUG')) $js_url .= '&debug=1';
		$userDivId = !isset($attr['divid']) || trim($attr['divid']) == "" ? '' : trim($attr['divid']);

		$attr = array_change_key_case( (array) $attr, CASE_LOWER );

      	wp_enqueue_script(
            'ajax_script',
            plugins_url( $js_url,__FILE__ ),
            array('jquery')
        );

		$vars = array(
				'shortcode_attr'=>json_encode($attr),
            	'_plugin_home_url' =>plugins_url( "",__FILE__ ),
            	'_action' => $this->_prefix.'_executeFrontend',
            	'_isPremium'=>$this->isPremium(),
            	'_isUserLoggedin'=>is_user_logged_in(),
            	'_premJS'=>$this->isPremium() ? $this->getPremiumFunctions()->getJSFrontFile() : '',
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( $this->_js_nonce ),
                'ajaxActionPrefix' => $this->_prefix,
                'divPrefix' => $userDivId == "" ? $this->_prefix : $userDivId,
                'divId' => $this->_divId,
                'jsFiles' => plugins_url( 'validator.js?_v='.$this->_js_version, __FILE__ )
            );
		$vars['_messages'] = [
			'msgCheck0'=>$this->getOptions()->getOptionValue('textValidationMessage0'),
			'msgCheck1'=>$this->getOptions()->getOptionValue('textValidationMessage1'),
			'msgCheck2'=>$this->getOptions()->getOptionValue('textValidationMessage2'),
			'msgCheck3'=>$this->getOptions()->getOptionValue('textValidationMessage3'),
			'msgCheck4'=>$this->getOptions()->getOptionValue('textValidationMessage4'),
			'msgCheck5'=>$this->getOptions()->getOptionValue('textValidationMessage5'),
			'msgCheck6'=>$this->getOptions()->getOptionValue('textValidationMessage6')
		];
		$vars['_options']=$this->getOptions()->getOptionsOnlyPublic();

		if ($this->isPremium()) $this->getPremiumFunctions()->addJSFrontFile();

		// per script eine variable einbinden, die url hat den wp-admin prefix
		// damit im backend.js dann die richtige callback url genutzt werden kann
        wp_localize_script(
            'ajax_script',
            'Ajax_'.$this->_prefix, // name der injected variable
            $vars
        );
        $ret = '';
        if (!isset($attr['divid']) || trim($attr['divid']) == "") {
        	$ret = '<div id="'.$this->_divId.'">...loading...</div>';
        }
		return $ret;
	}
}
/**
 * Proper ob_end_flush() for all levels
 *
 * This replaces the WordPress `wp_ob_end_flush_all()` function
 * with a replacement that doesn't cause PHP notices.
 */
/*
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
	while ( ob_get_level() > 0 ) {
		@ob_end_flush();
	}
} );
*/
$sngmbhSerialcodesValidator = new sngmbhSerialcodesValidator();

add_action( 'plugins_loaded', 'sngmbhSerialcodeWooCommercePluginLoaded' );
function sngmbhSerialcodeWooCommercePluginLoaded() {
	if ( class_exists( 'WooCommerce' ) ) {
		include_once dirname(__FILE__).'/woocommerce-hooks.php';
	}
}

?>
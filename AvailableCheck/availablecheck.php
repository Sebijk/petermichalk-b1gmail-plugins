<?php
declare(strict_types=1);

/**
 * AvailableCheck Plugin
 * 
 * With this plugin you can check the availability and authentication
 * of SMTP and POP3 servers from the admin area.
 * 
 * @version 1.2.0
 * @since PHP 8.3
 * @license GPL
 */
class availablecheck extends BMPlugin 
{
	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 = 'page1';

	/**
	 * POP3 connection object
	 */
	private ?object $_pop3 = null;

	/**
	 * SMTP connection object
	 */
	private ?object $_smtp = null;

	/**
	 * PHP 8.3: Readonly properties for immutable values
	 */
	private readonly string $pluginName;
	private readonly string $pluginVersion;
	private readonly string $pluginAuthor;

	/**
	 * Plugin constructor
	 * 
	 * Initializes all plugin properties and configurations.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		// PHP 8.3: Initialize readonly properties
		$this->pluginName 			= 'AvailableCheck';
		$this->pluginVersion 		= '1.2.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "availablecheck_icon.png";
	}

	/**
	 * Admin handler for plugin pages
	 * 
	 * Manages navigation and display of admin pages.
	 * Creates tabs for different areas and forwards to corresponding
	 * template files.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global array $lang_admin Language variables for admin area
	 */
	public function AdminHandler(): void
	{
		global $tpl, $lang_admin;

		// Plugin call without action
		$action = $_REQUEST['action'] ?? self::ADMIN_PAGE1;

		// Tabs in admin area
		$tabs = [
			0 => [
				'title'		=> $lang_admin['overview'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/availablecheck_logo.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($_REQUEST['action'] === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('availablecheck.admin.tpl'));
			$this->_Page1();
		}
	}

	/**
	 * Language variables handler
	 * 
	 * Loads and defines all required language variables for the plugin.
	 * Overrides or extends existing language variables.
	 * 
	 * @param array $lang_user Reference to user language variables
	 * @param array $lang_client Reference to client language variables
	 * @param array $lang_custom Reference to custom language variables
	 * @param array $lang_admin Reference to admin language variables
	 * @param string $lang Current language
	 * @return void
	 * @global array $lang_user Global user language variables
	 */
	public function OnReadLang(array &$lang_user, array &$lang_client, array &$lang_custom, array &$lang_admin, string $lang): void
	{
		$lang_admin['availablecheck_name']		= "AvailableCheck";
		$lang_admin['availablecheck_text']		= "Mit dem Plugin AvailableCheck l&auml;sst sich die Verf&uuml;gbarkeit und die Anmeldung des SMTP- und des POP3-Servers &uuml;berpr&uuml;fen";
		
		if(strpos($lang, 'deutsch') !== false) {
			$lang_admin['availablecheck_connect']	= "Verbinden";
		} else {
			$lang_admin['availablecheck_connect']	= "Connect";
		}
	}

	/**
	 * Plugin installation
	 * 
	 * Performs all necessary steps for plugin installation.
	 * Creates database tables, configures settings and logs
	 * the installation process.
	 * 
	 * @return bool True on successful installation, false on errors
	 */
	public function Install(): bool
	{
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully installed.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Plugin uninstallation
	 * 
	 * Performs all necessary steps for plugin uninstallation.
	 * Removes database tables, cleans up configurations and logs
	 * the uninstallation process.
	 * 
	 * @return bool True on successful uninstallation, false on errors
	 */
	public function Uninstall(): bool
	{
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Main function for server availability check
	 * 
	 * This method is the core of the plugin. It:
	 * - Processes user input for server connection testing
	 * - Tests POP3 server connection and authentication
	 * - Tests SMTP server connection and authentication
	 * - Assigns template variables with connection results
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global array $bm_prefs b1gMail settings
	 * 
	 * @uses $_REQUEST['do'] Action parameter (must be 'check')
	 * @uses $_REQUEST['user'] Username for authentication
	 * @uses $_REQUEST['pass'] Password for authentication
	 */
	private function _Page1(): void
	{
		global $tpl, $bm_prefs;

		if(($_REQUEST['do'] ?? '') === 'check') {
			$tpl->assign('check', true);

			$user = $_REQUEST['user'] ?? '';
			$pass = $_REQUEST['pass'] ?? '';

			/**
			 * Load required classes if not already loaded
			 * These classes are needed for POP3 and SMTP connections
			 */
			if(!class_exists('BMPOP3')) {
				include(B1GMAIL_DIR . 'serverlib/pop3.class.php');
			}
	
			if(!class_exists('BMSMTP')) {
				include(B1GMAIL_DIR . 'serverlib/smtp.class.php');
			}
			
			/**
			 * Test POP3 connection and authentication
			 * Default port 110 for POP3
			 */
			if($this->ConnectToPOP3($bm_prefs['b1gmta_host'], 110, $user, $pass)) {
				$this->_pop3?->Disconnect();
			}
			
			/**
			 * Test SMTP connection and authentication
			 * Default port 25 for SMTP
			 */
			if($this->ConnectToSMTP($bm_prefs['b1gmta_host'], 25, $bm_prefs['b1gmta_host'], $user, $pass)) {
				$this->_smtp?->Disconnect();
			}
			
			/**
			 * Assign user credentials to template
			 * For display purposes only
			 */
			$tpl->assign('user', $user);
			$tpl->assign('pass', $pass);
		}
	}
	
	/**
	 * Connect to POP3 server and test authentication
	 * 
	 * Establishes a connection to the POP3 server and tests
	 * authentication with the provided credentials.
	 * 
	 * @param string $host POP3 server hostname
	 * @param int $port POP3 server port (usually 110)
	 * @param string $user Username for authentication
	 * @param string $pass Password for authentication
	 * @return bool True if connection and authentication successful, false otherwise
	 * @global object $tpl Template engine
	 */
	private function ConnectToPOP3(string $host, int $port, string $user, string $pass): bool
	{
		global $tpl;

		/**
		 * Create POP3 connection object
		 * Uses b1gMail's BMPOP3 class
		 */
		$this->_pop3 = _new('BMPOP3', [$host, $port]);
		
		/**
		 * Attempt to connect to POP3 server
		 * If connection fails, set status and return false
		 */
		if(!$this->_pop3->Connect()) {
			$tpl->assign('pop3status1', false);
			$tpl->assign('pop3status2', false);
			return false;
		}
		$tpl->assign('pop3status1', true);
		
		/**
		 * Attempt to authenticate with POP3 server
		 * If authentication fails, set status and return false
		 */
		if(!$this->_pop3->Login($user, $pass)) {
			$tpl->assign('pop3status2', false);
			return false;
		}
		
		$tpl->assign('pop3status2', true);
		return true;
	}

	/**
	 * Connect to SMTP server and test authentication
	 * 
	 * Establishes a connection to the SMTP server and tests
	 * authentication with the provided credentials.
	 * 
	 * @param string $host SMTP server hostname
	 * @param int $port SMTP server port (usually 25)
	 * @param string $my_host Local hostname for SMTP HELO
	 * @param string $user Username for authentication
	 * @param string $pass Password for authentication
	 * @return bool True if connection and authentication successful, false otherwise
	 * @global object $tpl Template engine
	 */
	private function ConnectToSMTP(string $host, int $port, string $my_host, string $user, string $pass): bool
	{
		global $tpl;

		/**
		 * Create SMTP connection object
		 * Uses b1gMail's BMSMTP class
		 */
		$this->_smtp = _new('BMSMTP', [$host, $port, $my_host]);
		
		/**
		 * Attempt to connect to SMTP server
		 * If connection fails, set status and return false
		 */
		if(!$this->_smtp->Connect()) {
			$tpl->assign('smtpstatus1', false);
			$tpl->assign('smtpstatus2', false);
			return false;
		}
		$tpl->assign('smtpstatus1', true);
		
		/**
		 * Attempt to authenticate with SMTP server
		 * If authentication fails, set status and return false
		 */
		if(!$this->_smtp->Login($user, $pass)) {
			$tpl->assign('smtpstatus2', false);
			return false;
		}
		
		$tpl->assign('smtpstatus2', true);
		return true;
	}
}

/**
 * Plugin registration
 * 
 * Registers the plugin in the b1gMail plugin system.
 * This line must be at the end of the file so that the plugin
 * is recognized and loaded by b1gMail.
 * 
 * @global object $plugins b1gMail plugin manager
 */
$plugins->registerPlugin('availablecheck');
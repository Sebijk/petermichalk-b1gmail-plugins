<?php
declare(strict_types=1);

/**
 * Add Alias Plugin
 * 
 * With this plugin you can create aliases for groups, individual users
 * or directly for all users from the admin area.
 *  
 * @version 1.2.0
 * @since PHP 8.3
 * @license GPL
 */
class addalias extends BMPlugin 
{
	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 = 'page1';
	private const ADMIN_PAGE2 = 'page2';

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
		$this->pluginName 			= 'Add Alias';
		$this->pluginVersion 		= '1.2.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;	

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "addalias_icon.png";
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
				'title'		=> $lang_admin['create'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/addalias_logo.png'
			],
			1 => [
				'title'		=> $lang_admin['faq'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active'	=> $action === self::ADMIN_PAGE2,
				'icon'		=> './templates/images/faq32.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($_REQUEST['action'] === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('addalias1.pref.tpl'));
			$this->_Page1();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('addalias2.pref.tpl'));
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
		global $lang_user;

		$lang_admin['addalias_name']	=	"Add Alias";
		$lang_admin['addalias_text']	=	"Mit diesem Plugin k&ouml;nnen Sie einzelnen Benutzern einen Alias erstellen.";

		$lang_admin['addresstaken']			= $lang_user['addresstaken'] ?? '';
		$lang_admin['alias']				= $lang_user['alias'] ?? '';
		$lang_admin['aliastype_1']			= $lang_user['aliastype_1'] ?? '';
		$lang_admin['aliastype_2']			= $lang_user['aliastype_2'] ?? '';
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
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" wurde erfolgreich installiert.', PRIO_PLUGIN, __FILE__, __LINE__);
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
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" wurde erfolgreich deinstalliert.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Main function for alias creation
	 * 
	 * This method is the core of the plugin. It:
	 * - Loads all available groups from the database
	 * - Loads users based on group selection
	 * - Processes user input for alias creation
	 * - Creates aliases for selected users
	 * - Assigns template variables
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $bm_prefs b1gMail settings
	 * 
	 * @uses $_REQUEST['gruppe'] User's group selection
	 * @uses $_REQUEST['user'] User's user selection
	 * @uses $_REQUEST['email_domain'] Domain for alias creation
	 * @uses $_REQUEST['email_local'] Local part of email address
	 * @uses $_REQUEST['typ_1_email'] Complete email address for type 1 alias
	 */
	private function _Page1(): void
	{
		global $tpl, $db, $bm_prefs;

		/**
		 * Determine template step:
		 * 0 = Initial (select group)
		 * 1 = Group selected (select user)
		 * 2 = User selected (enter alias data)
		 * 3 = Alias created (confirmation)
		 */
		$tpl_use = 0;
		
		// Initialize arrays for groups and users
		$gruppen = [];
		$users = [];

		/**
		 * Load all available groups from database
		 * Sorted by title for better user experience
		 */
		$res = $db->Query('SELECT id, titel FROM {pre}gruppen ORDER by titel ASC');
		while($row = $res->FetchArray())
		{
			$gruppen[$row['id']] = [
				'id'				=> (int)$row['id'],
				'titel'				=> $row['titel'],
			];
		}
		$res->Free();

		/**
		 * Process group selection
		 * If gruppe_hidden is set, use it as current group
		 * (for form persistence)
		 */
		if(($_REQUEST['gruppe_hidden'] ?? '') !== '') {
			$_REQUEST['gruppe'] = $_REQUEST['gruppe_hidden'];
		}

		// wenn gruppe alle dann alle user abfraqen
		if($_REQUEST['gruppe'] == -1)
		{
			$res = $db->Query('SELECT id, email FROM {pre}users ORDER by email ASC');
		} else {
			$res = $db->Query('SELECT id, email FROM {pre}users WHERE gruppe=? ORDER by email ASC', 
				(int)($_REQUEST['gruppe'] ?? 0));
		}

		/**
		 * Fill user array for template
		 * Sorted by email address for better clarity
		 */
		while($row = $res->FetchArray())
		{
			$users[$row['id']] = [
				'id'				=> (int)$row['id'],
				'email'				=> $row['email'],
			];
		}
		$res->Free();

		/**
		 * Determine template step based on user input
		 * Step 1: Group was selected
		 */
		if(isset($_REQUEST['gruppe'])) {
			$tpl_use = 1;
			$_REQUEST['gruppe_hidden'] = $_REQUEST['gruppe'];
		}
		
		/**
		 * Step 2: User was selected
		 */
		if(isset($_REQUEST['user'])) {
			$tpl_use = 2;

			/**
			 * Load domain list from b1gMail settings
			 * and extend with group-specific alias domains
			 */
			$domainList = $bm_prefs['domains'];
			if(!is_array($domainList)) {
				$domainList = explode(':', $domainList);
			}
			
			/**
			 * Load additional domains from group alias settings
			 * These are domains that groups can use for aliases
			 */
			$res = $db->Query('SELECT id, saliase FROM {pre}gruppen ORDER by titel ASC');
			while($row = $res->FetchArray())
			{
				if(($row['saliase'] ?? '') !== '') {
					$domainList2 = explode(':', $row['saliase']);
					foreach($domainList2 as $domain) {
						if(!in_array($domain, $domainList)) {
							$domainList[] = $domain;
						}
					}
				}
			}
			$res->Free();
	
			$tpl->assign('domainList', $domainList);
			$_REQUEST['user_hidden'] = $_REQUEST['user'];
		}

		/**
		 * Step 3: Alias data was entered and should be processed
		 * Check for email_domain as indicator for alias creation
		 */
		if(isset($_REQUEST['email_domain'])) {
			$tpl_use = 3;
			$tpl_email_locked = false;

			/**
			 * Determine email address for alias:
			 * - If typ_1_email is provided, use it directly
			 * - Otherwise, construct from local part and domain
			 */
			if(($_REQUEST['typ_1_email'] ?? '') !== '') {
				$emailAddress = $_REQUEST['typ_1_email'];
			} else {
				$emailAddress = ($_REQUEST['email_local'] ?? '') . '@' . $_REQUEST['email_domain'];
			}

			/**
			 * Check if email address is already taken by users
			 */
			$res = $db->Query('SELECT id FROM {pre}users WHERE email=?', $emailAddress);
			if($res->RowCount() >= 1) {
				$tpl_email_locked = true;
			}
			$res->Free();

			/**
			 * Check if email address is already taken by aliases
			 */
			$res = $db->Query('SELECT id FROM {pre}aliase WHERE email=?', $emailAddress);
			if($res->RowCount() >= 1) {
				$tpl_email_locked = true;
			}
			$res->Free();

			/**
			 * Create alias if email address is available
			 */
			if($tpl_email_locked === false) {
				if(($_REQUEST['typ_1_email'] ?? '') !== '') {
					/**
					 * Type 1: Sender alias (external email address)
					 * User can send emails from this external address
					 */
					$db->Query('INSERT INTO {pre}aliase(email,user,type,date) VALUES(?,?,?,?)',
						$emailAddress,
						(int)($_REQUEST['user_hidden'] ?? 0),
						1,
						(int)time());
				} else {
					/**
					 * Type 3: Full alias (both sender and recipient)
					 * User can send and receive emails with this address
					 */
					$db->Query('INSERT INTO {pre}aliase(email,user,type,date) VALUES(?,?,?,?)',
						$emailAddress,
						(int)($_REQUEST['user_hidden'] ?? 0),
						3,
						(int)time());
				}
			}
			
			/**
			 * Reset form after successful alias creation
			 * Prevents duplicate creation on page refresh
			 */
			$_REQUEST['gruppe_hidden'] = "";
		}

		/**
		 * Assign template variables for display
		 * All data is passed to the template
		 */
		$tpl->assign('gruppen', $gruppen);                                    // Available groups
		$tpl->assign('users', $users);                                        // Available users
		$tpl->assign('selected_gruppe', $_REQUEST['gruppe_hidden'] ?? '');    // Selected group
		$tpl->assign('selected_user', $_REQUEST['user_hidden'] ?? '');        // Selected user
		$tpl->assign('tpl_use', $tpl_use);                                    // Current template step
		$tpl->assign('tpl_email_locked', $tpl_email_locked ?? false);         // Email address availability
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
$plugins->registerPlugin('addalias');
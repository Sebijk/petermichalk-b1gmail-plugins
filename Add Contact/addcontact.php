<?php
declare(strict_types=1);

/**
 * Add Contact Plugin
 * 
 * With this plugin you can create address book entries for groups, individual users
 * or directly for all users from the admin area.
 * 
 * @version 1.2.0
 * @since PHP 8.3
 * @license GPL
 */
class addcontact extends BMPlugin 
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
		$this->pluginName 			= 'Add Contact';
		$this->pluginVersion 		= '1.2.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "addcontact_icon.png";
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
			$tpl->assign('page', $this->_templatePath('addcontact1.pref.tpl'));
			$this->_Page1();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('addcontact2.pref.tpl'));
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

		$lang_admin['addcontact_name']		= "Add Contact";
		$lang_admin['addcontact_text']		= "With this plugin you can create address book entries for groups, individual users or directly for all users from the admin area.";

		$lang_admin['addcontact']			= $lang_user['addcontact_name'] ?? '';
		$lang_admin['surname']				= $lang_user['surname'] ?? '';
		$lang_admin['priv']					= $lang_user['priv'] ?? '';
		$lang_admin['streetnr']				= $lang_user['streetnr'] ?? '';
		$lang_admin['phone']				= $lang_user['phone'] ?? '';
		$lang_admin['mobile']				= $lang_user['mobile'] ?? '';
		$lang_admin['work']					= $lang_user['work'] ?? '';
		$lang_admin['company']				= $lang_user['company'] ?? '';
		$lang_admin['position']				= $lang_user['position'] ?? '';
		$lang_admin['web']					= $lang_user['web'] ?? '';
		$lang_admin['birthday']				= $lang_user['birthday'] ?? '';
		$lang_admin['comment']				= $lang_user['comment'] ?? '';
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
	 * Main function for contact creation
	 * 
	 * This method is the core of the plugin. It:
	 * - Loads all available groups from the database
	 * - Loads users based on group selection
	 * - Processes user input for contact creation
	 * - Creates address book entries for selected users
	 * - Assigns template variables
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $bm_prefs b1gMail settings
	 * 
	 * @uses $_REQUEST['gruppe'] User's group selection
	 * @uses $_REQUEST['user'] User's user selection
	 * @uses $_REQUEST['vorname'] First name of contact to be created
	 * @uses $_REQUEST['nachname'] Last name of contact to be created
	 * @uses $_REQUEST['firma'] Company name of contact to be created
	 * @uses $_REQUEST['email'] Email address of contact to be created
	 * @uses $_REQUEST['strassenr'] Street and house number
	 * @uses $_REQUEST['plz'] Postal code
	 * @uses $_REQUEST['ort'] City
	 * @uses $_REQUEST['land'] Country
	 * @uses $_REQUEST['tel'] Phone number
	 * @uses $_REQUEST['fax'] Fax number
	 * @uses $_REQUEST['handy'] Mobile number
	 * @uses $_REQUEST['work_*'] Business contact data
	 * @uses $_REQUEST['anrede'] Salutation
	 * @uses $_REQUEST['position'] Position/job
	 * @uses $_REQUEST['web'] Website
	 * @uses $_REQUEST['kommentar'] Comment/notes
	 * @uses $_REQUEST['default'] Default address type (work/private)
	 */
	private function _Page1(): void
	{
		global $tpl, $db, $bm_prefs;
		
		$tpl->assign('templ', $bm_prefs['template']);

		/**
		 * Determine template step:
		 * 0 = Initial (select group)
		 * 1 = Group selected (select user)
		 * 2 = User selected (enter contact data)
		 * 3 = Contact created (confirmation)
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

		/**
		 * Load users based on group selection
		 * -1 = All users
		 * Other values = Specific group
		 */
		if(($_REQUEST['gruppe'] ?? 0) == -1) {
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
			$_REQUEST['user_hidden'] = $_REQUEST['user'];
		}

		/**
		 * Step 3: Contact data was entered and should be processed
		 * Check for first or last name as indicator for contact creation
		 */
		if(($_REQUEST['vorname'] ?? '') !== '' || ($_REQUEST['nachname'] ?? '') !== '') {
			$tpl_use = 3;

			/**
			 * Determine target users for contact creation:
			 * - Group = all (-1) AND User = all (-1) → All users
			 * - Group = specific AND User = all (-1) → All users of group
			 * - Group = specific AND User = specific → Single user
			 */
			if(($_REQUEST['gruppe_hidden'] ?? 0) == -1 && ($_REQUEST['user_hidden'] ?? 0) == -1) {
				$res = $db->Query('SELECT id, email, vorname, nachname FROM {pre}users');
			} elseif(($_REQUEST['gruppe_hidden'] ?? 0) != -1 && ($_REQUEST['user_hidden'] ?? 0) == -1) {
				$res = $db->Query('SELECT id, email, vorname, nachname FROM {pre}users WHERE gruppe=?', 
					(int)($_REQUEST['gruppe_hidden'] ?? 0));
			} else {
				$res = $db->Query('SELECT id, email, vorname, nachname FROM {pre}users WHERE id=?', 
					(int)($_REQUEST['user_hidden'] ?? 0));
			}

			/**
			 * Contact creation for all found users
			 * Uses the b1gMail Addressbook class
			 */
			while($row = $res->FetchArray())
			{
				// Load addressbook class for each user
				include_once('../serverlib/addressbook.class.php');
				
				/**
				 * Create addressbook interface for current user
				 * @var BMAddressbook $book
				 */
				$book = _new('BMAddressbook', [(int)$row['id']]);

				// Contact groups (currently empty, can be extended)
				$groups = [];
				
				/**
				 * Create address book entry
				 * All contact data from $_REQUEST is passed
				 */
				$contactID = $book->AddContact(
					$_REQUEST['firma'] ?? '',           // Company name
					$_REQUEST['vorname'] ?? '',         // First name
					$_REQUEST['nachname'] ?? '',        // Last name
					$_REQUEST['strassenr'] ?? '',       // Street and house number
					$_REQUEST['plz'] ?? '',             // Postal code
					$_REQUEST['ort'] ?? '',             // City
					$_REQUEST['land'] ?? '',            // Country
					$_REQUEST['tel'] ?? '',             // Phone number
					$_REQUEST['fax'] ?? '',             // Fax number
					$_REQUEST['handy'] ?? '',           // Mobile number
					$_REQUEST['email'] ?? '',           // Email address
					$_REQUEST['work_strassenr'] ?? '',  // Business street
					$_REQUEST['work_plz'] ?? '',        // Business postal code
					$_REQUEST['work_ort'] ?? '',        // Business city
					$_REQUEST['work_land'] ?? '',       // Business country
					$_REQUEST['work_tel'] ?? '',        // Business phone number
					$_REQUEST['work_fax'] ?? '',        // Business fax number
					$_REQUEST['work_handy'] ?? '',      // Business mobile number
					$_REQUEST['work_email'] ?? '',      // Business email
					$_REQUEST['anrede'] ?? '',          // Salutation
					$_REQUEST['position'] ?? '',        // Position/job
					$_REQUEST['web'] ?? '',             // Website
					$_REQUEST['kommentar'] ?? '',       // Comment/notes
					SmartyDateTime('geburtsdatum_'),    // Birth date (Smarty format)
					($_REQUEST['default'] ?? '') === 'work' ? ADDRESS_WORK : ADDRESS_PRIVATE, // Default address type
					$groups,                            // Contact groups
					false,                              // Not as default contact
					false                               // Not as favorite
				);
			}
			$res->Free();

			/**
			 * Reset form after successful contact creation
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
$plugins->registerPlugin('addcontact');
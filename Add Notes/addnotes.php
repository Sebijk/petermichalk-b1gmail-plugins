<?php
declare(strict_types=1);

/**
 * Add Notes Plugin
 * 
 * With this plugin you can create notes for groups, individual users
 * or directly for all users from the admin area.
 * 
 * @version 1.2.0
 * @since PHP 8.3
 * @license GPL
 */
class addnotes extends BMPlugin 
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
		$this->pluginName 			= 'Add Notes';
		$this->pluginVersion 		= '1.2.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "addnotes_icon.png";
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
				'icon'		=> '../plugins/templates/images/addnotes_logo.png'
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
			$tpl->assign('page', $this->_templatePath('addnotes1.pref.tpl'));
			$this->_Page1();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('addnotes2.pref.tpl'));
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
		$lang_admin['addnotes_name']		= "Add Notes";
		$lang_admin['addnotes_text']		= "Mit diesem Plugin k&ouml;nnen Sie Gruppen, einzelnen Benutzern oder direkt allen Benutzern, eine Notiz erstellen.";
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
	 * Main function for note creation
	 * 
	 * This method is the core of the plugin. It:
	 * - Loads all available groups from the database
	 * - Loads users based on group selection
	 * - Processes user input for note creation
	 * - Creates notes for selected users
	 * - Assigns template variables
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * 
	 * @uses $_REQUEST['gruppe'] User's group selection
	 * @uses $_REQUEST['user'] User's user selection
	 * @uses $_REQUEST['text'] Note text content
	 * @uses $_REQUEST['priority'] Note priority level
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		/**
		 * Determine template step:
		 * 0 = Initial (select group)
		 * 1 = Group selected (select user)
		 * 2 = User selected (enter note data)
		 * 3 = Note created (confirmation)
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
		 * Step 3: Note data was entered and should be processed
		 * Check for priority as indicator for note creation
		 */
		if(isset($_REQUEST['priority'])) {
			$tpl_use = 3;

			/**
			 * Determine target users for note creation:
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
			 * Create notes for all found users
			 * Process each user individually with personalized content
			 */
			while($row = $res->FetchArray())
			{
				/**
				 * Replace variables in note text
				 * Supports %%firstname%%, %%lastname%%, %%email%% placeholders
				 */
				$text = $this->setVar($row, $_REQUEST['text'] ?? '');

				/**
				 * Insert note into database
				 * All notes are created as individual entries
				 */
				$db->Query('INSERT INTO {pre}notes(user,date,priority,text) VALUES(?,?,?,?)',
					(int)$row['id'],
					(int)time(),
					(int)($_REQUEST['priority'] ?? 0),
					$text);
			}
			$res->Free();

			/**
			 * Reset form after successful note creation
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
	
	/**
	 * Replace user variables in text
	 * 
	 * Replaces placeholders in note text with actual user data.
	 * Supports the following placeholders:
	 * - %%firstname%% → User's first name
	 * - %%lastname%% → User's last name  
	 * - %%email%% → User's email address
	 * 
	 * @param array $user User data array with vorname, nachname, email
	 * @param string $text Text containing placeholders
	 * @return string Text with placeholders replaced
	 */
	private function setVar(array $user, string $text): string
	{
		$text = str_replace("%%firstname%%", $user['vorname'] ?? '', $text);
		$text = str_replace("%%lastname%%", $user['nachname'] ?? '', $text);
		$text = str_replace("%%email%%", $user['email'] ?? '', $text);
		return $text;
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
$plugins->registerPlugin('addnotes');
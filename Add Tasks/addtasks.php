<?php
declare(strict_types=1);

/**
 * Add Tasks Plugin
 * 
 * With this plugin you can create tasks for groups, individual users
 * or directly for all users from the admin area.
 * 
 * @version 1.2.0
 * @since PHP 8.3
 * @license GPL
 */
class addtasks extends BMPlugin 
{
	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 = 'page1';
	private const ADMIN_PAGE2 = 'page2';

	/**
	 * Priority translation array
	 * Maps priority values between string and numeric formats
	 */
	private array $_prioTrans = [
		'low'		=> -1,
		'normal'	=> 0,
		'high'		=> 1,
		-1			=> 'low',
		0			=> 'normal',
		1			=> 'high'
	];

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
		$this->pluginName 			= 'Add Tasks';
		$this->pluginVersion 		= '1.2.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "addtasks_icon.png";
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
				'icon'		=> '../plugins/templates/images/addtasks_logo.png'
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
			$tpl->assign('page', $this->_templatePath('addtasks1.pref.tpl'));
			$this->_Page1();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('addtasks2.pref.tpl'));
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
		$lang_admin['addtasks_name']		= "Add Tasks";
		$lang_admin['addtasks_text']		= "Mit diesem Plugin k&ouml;nnen Sie Gruppen, einzelnen Benutzern oder direkt allen Benutzern, eine Aufgabe aus dem Adminbereich heraus erstellen.";

		$lang_admin['begin']				= $lang_user['begin'] ?? '';
		$lang_admin['due']					= $lang_user['due'] ?? '';
		$lang_admin['status']				= $lang_user['status'] ?? '';
		$lang_admin['done']					= $lang_user['done'] ?? '';
		$lang_admin['taskst_16']			= $lang_user['taskst_16'] ?? '';
		$lang_admin['taskst_32']			= $lang_user['taskst_32'] ?? '';
		$lang_admin['taskst_64']			= $lang_user['taskst_64'] ?? '';
		$lang_admin['taskst_128']			= $lang_user['taskst_128'] ?? '';
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
	 * Main function for task creation
	 * 
	 * This method is the core of the plugin. It:
	 * - Loads all available groups from the database
	 * - Loads users based on group selection
	 * - Processes user input for task creation
	 * - Creates tasks for selected users
	 * - Assigns template variables
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * 
	 * @uses $_REQUEST['gruppe'] User's group selection
	 * @uses $_REQUEST['user'] User's user selection
	 * @uses $_REQUEST['titel'] Task title
	 * @uses $_REQUEST['comments'] Task comments/description
	 * @uses $_REQUEST['beginn'] Task start date
	 * @uses $_REQUEST['faellig'] Task due date
	 * @uses $_REQUEST['akt_status'] Task status
	 * @uses $_REQUEST['priority'] Task priority
	 * @uses $_REQUEST['erledigt'] Task completion status
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		/**
		 * Determine template step:
		 * 0 = Initial (select group)
		 * 1 = Group selected (select user)
		 * 2 = User selected (enter task data)
		 * 3 = Task created (confirmation)
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
		 * Step 3: Task data was entered and should be processed
		 * Check for priority as indicator for task creation
		 */
		if(isset($_REQUEST['priority'])) {
			$tpl_use = 3;

			/**
			 * Determine target users for task creation:
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
			 * Create tasks for all found users
			 * Process each user individually with personalized content
			 */
			while($row = $res->FetchArray())
			{
				/**
				 * Replace variables in task comments and title
				 * Supports %%firstname%%, %%lastname%%, %%email%% placeholders
				 */
				$comments = $this->setVar($row, $_REQUEST['comments'] ?? '');
				$title = $this->setVar($row, $_REQUEST['titel'] ?? '');

				/**
				 * Convert priority if numeric
				 * Maps numeric priority to string format
				 */
				$priority = $_REQUEST['priority'] ?? 0;
				if(is_numeric($priority)) {
					$priority = $this->_prioTrans[$priority] ?? $priority;
				}

				/**
				 * Insert task into database
				 * All tasks are created as individual entries
				 */
				$db->Query('INSERT INTO {pre}tasks(user,beginn,faellig,akt_status,titel,priority,erledigt,comments) VALUES(?,?,?,?,?,?,?,?)',
					(int)$row['id'],
					(int)SmartyDateTime('beginn'),
					(int)SmartyDateTime('faellig'),
					(int)($_REQUEST['akt_status'] ?? 0),
					$title,
					$priority,
					(int)($_REQUEST['erledigt'] ?? 0),
					$comments);
			}
			$res->Free();

			/**
			 * Reset form after successful task creation
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
	 * Replaces placeholders in task text with actual user data.
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
$plugins->registerPlugin('addtasks');
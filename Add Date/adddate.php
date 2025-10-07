<?php
declare(strict_types=1);

/**
 * Add Date Plugin
 * 
 * With this plugin you can create calendar events for groups, individual users
 * or directly for all users from the admin area.
 * 
 * @version 1.2.0
 * @since PHP 8.2
 * @license GPL
 */
class adddate extends BMPlugin 
{
	/**
	 * Plugin constants
	 */
	private const PLUGIN_NAME 			= 'Add Date';
	private const PLUGIN_VERSION 		= '1.2.0';
	private const PLUGIN_DESIGNEDFOR 	= '7.4.1';
	private const PLUGIN_AUTHOR 		= 'Peter Michalk';

	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 			= 'page1';
	private const ADMIN_PAGE2 			= 'page2';

	/**
	 * Plugin constructor
	 * 
	 * Initializes all plugin properties and configurations.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->name					= self::PLUGIN_NAME;
		$this->version				= self::PLUGIN_VERSION;
		$this->designedfor			= self::PLUGIN_DESIGNEDFOR;
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= self::PLUGIN_AUTHOR;

		$this->admin_pages			= true;
		$this->admin_page_title		= self::PLUGIN_NAME;
		$this->admin_page_icon		= 'adddate_icon.png';
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

		$tabs = [
			0 => [
				'title'		=> $lang_admin['create'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/adddate_logo.png'
			],
			1 => [
				'title'		=> $lang_admin['faq'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active'	=> $action === self::ADMIN_PAGE2,
				'icon'		=> './templates/images/faq32.png'
			]
		];

		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('adddate1.pref.tpl'));
			$this->_Page1();
		} elseif($action === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('adddate2.pref.tpl'));
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
	public function OnReadLang(&$lang_user, &$lang_client, &$lang_custom, &$lang_admin, $lang): void
	{
		$lang_admin['adddate_name']		= 'Add Date';
		$lang_admin['adddate_text']		= 'Mit diesem Plugin können Sie Gruppen, einzelnen Benutzern oder direkt allen Benutzern, einen Termin erstellen.';

		$lang_admin['begin']			= $lang_user['begin'] ?? '';
		$lang_admin['day']				= $lang_user['day'] ?? '';
		$lang_admin['week']				= $lang_user['week'] ?? '';
		$lang_admin['month']			= $lang_user['month'] ?? '';
		$lang_admin['adddate']			= $lang_user['adddate'] ?? '';
		$lang_admin['nocalcat']			= $lang_user['nocalcat'] ?? '';
		$lang_admin['date2']			= $lang_user['date2'] ?? '';
		$lang_admin['close']			= $lang_user['close'] ?? '';
		$lang_admin['attendees']		= $lang_user['attendees'] ?? '';
		$lang_admin['none']				= $lang_user['none'] ?? '';
		$lang_admin['end']				= $lang_user['end'] ?? '';
		$lang_admin['location']			= $lang_user['location'] ?? '';
		$lang_admin['reminder']			= $lang_user['reminder'] ?? '';
		$lang_admin['btr']				= $lang_user['btr'] ?? '';
		$lang_admin['wholeday']			= $lang_user['wholeday'] ?? '';
		$lang_admin['thisevent']		= $lang_user['thisevent'] ?? '';
		$lang_admin['color']			= $lang_user['color'] ?? '';
		$lang_admin['dates']			= $lang_user['dates'] ?? '';
		$lang_admin['dates2']			= $lang_user['dates2'] ?? '';
		$lang_admin['duration']			= $lang_user['duration'] ?? '';
		$lang_admin['hours']			= $lang_user['hours'] ?? '';
		$lang_admin['minutes']			= $lang_user['minutes'] ?? '';
		$lang_admin['byemail']			= $lang_user['byemail'] ?? '';
		$lang_admin['bysms']			= $lang_user['bysms'] ?? '';
		$lang_admin['timeframe']		= $lang_user['timeframe'] ?? '';
		$lang_admin['timebefore']		= $lang_user['timebefore'] ?? '';

		$lang_admin['every']			= $lang_user['every'] ?? '';
		$lang_admin['days']				= $lang_user['days'] ?? '';
		$lang_admin['weeks']			= $lang_user['weeks'] ?? '';
		$lang_admin['months']			= $lang_user['months'] ?? '';
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
		// log
		PutLog(sprintf('%s v%s installed',
			$this->name,
			$this->version),
			PRIO_PLUGIN,
			__FILE__,
			__LINE__);

		return(true);
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
		PutLog(sprintf('%s v%s uninstalled',
			$this->name,
			$this->version),
			PRIO_PLUGIN,
			__FILE__,
			__LINE__);

		return(true);
	}

	/**
	 * Main function for calendar event creation
	 * 
	 * This method is the core of the plugin. It:
	 * - Loads all available groups from the database
	 * - Loads users based on group selection
	 * - Processes user input for calendar event creation
	 * - Creates calendar events for selected users
	 * - Assigns template variables
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * 
	 * @uses $_REQUEST['gruppe'] User's group selection
	 * @uses $_REQUEST['user'] User's user selection
	 * @uses $_REQUEST['title'] Event title
	 * @uses $_REQUEST['text'] Event description
	 * @uses $_REQUEST['location'] Event location
	 * @uses $_REQUEST['startdate'] Event start date
	 * @uses $_REQUEST['wholeDay'] Whether event is all-day
	 * @uses $_REQUEST['durationHours'] Event duration in hours
	 * @uses $_REQUEST['durationMinutes'] Event duration in minutes
	 * @uses $_REQUEST['reminder'] Reminder time in minutes
	 * @uses $_REQUEST['reminder_email'] Email reminder flag
	 * @uses $_REQUEST['reminder_sms'] SMS reminder flag
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		/**
		 * Determine template step:
		 * 0 = Initial (select group)
		 * 1 = Group selected (select user)
		 * 2 = User selected (enter event data)
		 * 3 = Event created (confirmation)
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
		 * Step 3: Event data was entered and should be processed
		 * Check for title as indicator for event creation
		 */
		if(($_REQUEST['title'] ?? '') !== '') {
			$tpl_use = 3;

			/**
			 * Determine target users for event creation:
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
			 * Calculate event end date and set flags
			 * Handle both all-day and timed events
			 */
			$flags = 0;
			$enddate = 0;
			
			if(($_REQUEST['wholeDay'] ?? 0) == 1) {
				$flags |= CLNDR_WHOLE_DAY;
				$enddate = SmartyDateTime($_REQUEST['startdate'] ?? '') + 59;
			} else {
				$enddate = max(
					SmartyDateTime('startdate') + TIME_ONE_MINUTE,
					SmartyDateTime('startdate') 
					+ (int)($_REQUEST['durationHours'] ?? 0) * TIME_ONE_HOUR
					+ (int)($_REQUEST['durationMinutes'] ?? 0) * TIME_ONE_MINUTE
				);
			}

			/**
			 * Configure reminder settings
			 * Support both email and SMS reminders
			 */
			$reminder = max(0, (int)($_REQUEST['reminder'] ?? 0)) * TIME_ONE_MINUTE;
			if(isset($_REQUEST['reminder_email'])) {
				$flags |= CLNDR_REMIND_EMAIL;
			}
			if(isset($_REQUEST['reminder_sms'])) {
				$flags |= CLNDR_REMIND_SMS;
			}

			/**
			 * Create calendar events for all found users
			 * Process each user individually with personalized content
			 */
			while($row = $res->FetchArray())
			{
				/**
				 * Replace variables in event text and title
				 * Supports %%firstname%%, %%lastname%%, %%email%% placeholders
				 */
				$text = $this->setVar($row, $_REQUEST['text'] ?? '');
				$title = $this->setVar($row, $_REQUEST['title'] ?? '');

				/**
				 * Insert calendar event into database
				 * All events are created as individual entries (group = -1)
				 */
				$db->Query('INSERT INTO {pre}dates(user,title,location,text,`group`,startdate,enddate,reminder,flags,repeat_flags,repeat_times,repeat_value,repeat_extra1,repeat_extra2) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
					(int)$row['id'],
					$title,
					$_REQUEST['location'] ?? '',
					$text,
					-1,
					(int)SmartyDateTime('startdate'),
					(int)$enddate,
					$reminder,
					$flags,
					0,
					0,
					0,
					'',
					0);
			}
			$res->Free();

			/**
			 * Reset form after successful event creation
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
	 * Replaces placeholders in event text and title with actual user data.
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
$plugins->registerPlugin('adddate');
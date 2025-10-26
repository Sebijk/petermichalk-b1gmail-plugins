<?php
declare(strict_types=1);

/**
 * Check Your Password Plugin
 * 
 * Forces users to change their password after a specified time period.
 * Supports group-based configuration and secure password requirements.
 * 
 * @version 2.1.0
 * @since PHP 8.3
 * @license GPL
 */
class checkpassword extends BMPlugin 
{
	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 = 'page1';
	private const ADMIN_PAGE2 = 'page2';
	private const ADMIN_PAGE3 = 'page3';

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
		$this->pluginName 			= 'Check Your Password';
		$this->pluginVersion 		= '2.1.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "checkpassword_icon.png";
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
				'title'		=> $lang_admin['groups'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/checkpassword_logo.png'
			],
			1 => [
				'title'		=> $lang_admin['user'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active'	=> $action === self::ADMIN_PAGE2,
				'icon'		=> '../plugins/templates/images/checkpassword_logo.png'
			],
			2 => [
				'title'		=> $lang_admin['faq'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE3 . '&',
				'active'	=> $action === self::ADMIN_PAGE3,
				'icon'		=> './templates/images/faq32.png'
			]
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('checkpassword.page1.acp.tpl'));
			$this->_Page1();
		} elseif($action === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('checkpassword.page2.acp.tpl'));
			$this->_Page2();
		} elseif($action === self::ADMIN_PAGE3) {
			$tpl->assign('page', $this->_templatePath('checkpassword.page3.acp.tpl'));
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
		$lang_admin['checkpassword_name']						= "Check Your Password";
		$lang_admin['checkpassword_text']						= "Fragt den Benutzer nach einer bestimmten Zeit vor jedem Login nach einem neuem Passwort.";
		
		if (strpos($lang, 'deutsch') !== false) {
			$lang_custom['checkpassword_login_text'] 			= "Bitte legen Sie sich jetzt ein neues Passwort an!";
			$lang_admin['text_checkpassword_login_text']		= $this->name . ': Text';

			$lang_admin['checkpassword_password_expire']		= "Benutzer muss Kennwort bei der n&auml;chsten Anmeldung &auml;ndern";
			$lang_admin['checkpassword_password_never_expire']	= "Passwort l&auml;uft nie ab";
			$lang_admin['checkpassword_login_secure_password']	= "sicheres Passwort";
			$lang_admin['checkpassword_login_secure_password2']	= "sicheres Passwort erzwingen";

			$lang_user['checkpassword_login_new_password1']		= "Ihr neues Passwort";
			$lang_user['checkpassword_login_new_password2']		= "Neues Passwort wiederholen";
			$lang_user['checkpassword_strongpassword']			= "Bitte geben Sie ein sicheres Passwort ein.";
			$lang_user['checkpassword_login_securepassword']	= "Ein m&ouml;glichst sicheres Passwort besteht aus:</b><br /><br />* Mindestens 8 Zeichen<br />* Buchstaben UND Zahlen<br />* Umlauten und/oder Sonderzeichen<br />* Gro&szlig;- UND Kleinschreibung";
			$lang_user['checkpassword_login_gotomailbox']		= "weiter zum Postfach";
		} else {
			$lang_custom['checkpassword_login_text'] 			= "Please change your Password!";
			$lang_admin['text_checkpassword_login_text']		= $this->name . ': Text';

			$lang_admin['checkpassword_password_expire']		= "User must change password at next logon";
			$lang_admin['checkpassword_password_never_expire']	= "Password never expires";
			$lang_admin['checkpassword_login_secure_password']	= "secure Password";
			$lang_admin['checkpassword_login_secure_password2']	= "force secure Password";

			$lang_user['checkpassword_login_new_password1']		= $lang_user['password'] ?? '';
			$lang_user['checkpassword_login_new_password2']		= $lang_user['repeat'] ?? '';
			$lang_user['checkpassword_strongpassword']			= "Please use an strong Password.";
			$lang_user['checkpassword_login_securepassword']	= "Qualities of strong passwords:</b><br /><br />* 8 or more characters are the minimum for a strong password<br />* An ideal password combines both length and different types of symbols";
			$lang_user['checkpassword_login_gotomailbox']		= "next";
		}

		$lang_admin['timeframe']								= $lang_user['timeframe'] ?? '';
	}
	
	/**
	 * Plugin installation
	 * 
	 * Performs all necessary steps for plugin installation.
	 * Creates database tables, configures settings and logs
	 * the installation process.
	 * 
	 * @return bool True on successful installation, false on errors
	 * @global object $db Database connection
	 */
    public function Install(): bool
    {
		global $db;

		/**
		 * Create plugin_checkpassword_groups table
		 * Stores group-specific password policy settings
		 */
		$db->Query('CREATE TABLE IF NOT EXISTS `{pre}plugin_checkpassword_groups` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`groups` int(11) NOT NULL,
			`time` int(11) NOT NULL,
			`duty` int(1) NOT NULL,
			`secure` int(1) NOT NULL,
			PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 ;');

		$this->_datum_install();

		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully installed.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
    }

	// UserPrefs fuellen mit Werten fuer user
	function _datum_install()
	{
		global $db;
		$time_now = true;

		$res = $db->Query('SELECT id, reg_date FROM {pre}users');
		while($row = $res->FetchArray())
		{
			if($this->_GetPref('checkpassword_date', $row['id']) == false)
			{
				if($time_now)
				{
					$this->_SetPref('checkpassword_date', time(), $row['id']);
				} else {
					$this->_SetPref('checkpassword_date', $row['reg_date'], $row['id']);
				}
			}
		}
	}

	/**
	 * Plugin uninstallation
	 * 
	 * Performs all necessary steps for plugin uninstallation.
	 * Removes database tables, cleans up configurations and logs
	 * the uninstallation process.
	 * 
	 * @return bool True on successful uninstallation, false on errors
	 * @global object $db Database connection
	 */
    public function Uninstall(): bool
    {
		global $db;
		$db->Query('DROP TABLE {pre}plugin_checkpassword_groups');

		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
    }

	function _Page1()
	{
		global $tpl, $db;

		// delete 
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete')
		{
			//loeschen der seite
			$db->Query('Delete FROM {pre}plugin_checkpassword_groups WHERE id=?', 
				(int) $_REQUEST['id']);
		}

		// save
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			// DB INSEERT
			$res = $db->Query('INSERT INTO {pre}plugin_checkpassword_groups(groups, time, duty, secure) VALUES(?,?,?,?)', 
				(int) $_REQUEST['gruppe'],
				(int) $_REQUEST['time'],
				(int) isset($_REQUEST['duty']) ? 1 : 0,
				(int) isset($_REQUEST['secure']) ? 1 : 0);
		}

		$check_groups = $all_groups = array();
		
		$sortBy = isset($_REQUEST['sortBy']) ? $_REQUEST['sortBy'] : 'cg.id';
		$sortOrder = isset($_REQUEST['sortOrder']) ? strtolower($_REQUEST['sortOrder']) : 'asc';
		
		$res = $db->Query('SELECT cg.*, g.titel FROM {pre}plugin_checkpassword_groups as cg INNER JOIN {pre}gruppen as g ON cg.groups = g.id ORDER BY ' . $sortBy . ' ' . $sortOrder);
		while($row = $res->FetchArray())
		{
			$check_groups[$row['id']] = array(
				'id'					=> $row['id'],
				'title'					=> $row['titel'],
				'time'					=> $row['time'],
				'duty'					=> $row['duty'],
				'secure'				=> $row['secure'],
			);
		}
		$res->Free();

		$res = $db->Query('SELECT id, titel FROM {pre}gruppen');
		while($row = $res->FetchArray())
		{
			$res2 = $db->Query('SELECT time FROM {pre}plugin_checkpassword_groups WHERE groups=?',
				(int) $row['id']);
			if($res2->RowCount() == 0)
			{
				$all_groups[$row['id']] = array(
					'id'					=> $row['id'],
					'titel'					=> $row['titel'],
				);
			}
		}
		$res->Free();

		$tpl->assign('sortBy', $sortBy);
		$tpl->assign('sortOrder', $sortOrder);

		$tpl->assign('check_groups', $check_groups);
		$tpl->assign('all_groups', $all_groups);
		$tpl->assign('all_groups_count', count($all_groups));
	}
	
	function _Page2()
	{
		global $tpl, $db;

		// variable fuer template
		$tpl_use = 0;
		// gruppen und users arrays
		$gruppen = array();
		$users = array();

		// array gruppen fuellen
		$res = $db->Query('SELECT id, titel FROM {pre}gruppen ORDER by titel ASC');
		while($row = $res->FetchArray())
		{
			$gruppen[$row['id']] = array(
				'id'		=> $row['id'],
				'titel'		=> $row['titel'],
			);
		}
		$res->Free();

		// wenn gruppe_hidden benutzt wird, gruppe fuellen
		if($_REQUEST['gruppe_hidden'] != "")
		{
			$_REQUEST['gruppe'] = $_REQUEST['gruppe_hidden'];
		}

		// wenn gruppe alle dann alle user abfraqen
		if($_REQUEST['gruppe'] == -1)
		{
			$res = $db->Query('SELECT id, email FROM {pre}users ORDER by email ASC');
		} else {
			$res = $db->Query('SELECT id, email FROM {pre}users WHERE gruppe=? ORDER by email ASC', 
				(int ) $_REQUEST['gruppe']);
		}

		// array users fuellen
		while($row = $res->FetchArray())
		{
			$users[$row['id']] = array(
				'id'		=> $row['id'],
				'email'		=> $row['email'],
			);
		}
		$res->Free();

		// template variable je nach fortschritt aendern
		if(isset($_REQUEST['gruppe']))
		{
			$tpl_use = 1;

			$_REQUEST['gruppe_hidden'] = $_REQUEST['gruppe'];
		}
		if(isset($_REQUEST['user']))
		{
			$tpl_use = 2;

			$_REQUEST['user_hidden'] = $_REQUEST['user'];
		}

		// db speichern
		if($_REQUEST['user_hidden'] != "" AND !isset($_REQUEST['user']))
		{
			$tpl_use = 3;

			// gruppe = alle und user = alle, alle user
			// gruppe = gruppeid und user = alle, alle user von gruppe
			// gruppe = gruppeid und user = userid, user waehlen
			if($_REQUEST['gruppe_hidden'] == -1 AND $_REQUEST['user_hidden'] == -1)
			{
				$res = $db->Query('SELECT id, email, vorname, nachname FROM {pre}users');
			} else if($_REQUEST['gruppe_hidden'] != -1 AND $_REQUEST['user_hidden'] == -1) {
				$res = $db->Query('SELECT id, email, vorname, nachname FROM {pre}users WHERE gruppe=?',
					(int) $_REQUEST['gruppe_hidden']);
			} else {
				$res = $db->Query('SELECT id, email, vorname, nachname FROM {pre}users WHERE id=?', 
					(int) $_REQUEST['user_hidden']);
			}

			//db fuellen
			while($row = $res->FetchArray())
			{
				$this->_SetPref('checkpassword_date', $_REQUEST['expire'], $row['id']);
			}
			$res->Free();

			// Gruppe leeren
			$_REQUEST['gruppe_hidden'] = "";
		}

		// template variablen
		$tpl->assign('gruppen', $gruppen);
		$tpl->assign('users', $users);	
		$tpl->assign('selected_gruppe', $_REQUEST['gruppe_hidden']);
		$tpl->assign('selected_user', $_REQUEST['user_hidden']);
		$tpl->assign('tpl_use', $tpl_use);
	}

	/*
	 * set preference
	 */
	function _SetPref($key, $value)
	{
		global $db, $user;

		$db->Query('REPLACE INTO {pre}userprefs(userID, `key`,`value`) VALUES(?, ?, ?)',
			(int)$user,
			$key,
			$value);
		return($db->AffectedRows() == 1);
	}

	/*
	 * get preference
	 */
	function _GetPref($key)
	{
		global $db, $user;

		$res = $db->Query('SELECT `value` FROM {pre}userprefs WHERE userID=? AND `key`=?',
			(int)$user,
			$key);
		if($res->RowCount() == 1)
		{
			$row = $res->FetchArray(MYSQLI_NUM);
			$res->Free();
			return($row[0]);
		}
		else 
		{
			$res->Free();
			return(false);
		}
	}

	/*
	 * delete preference
	 */
	function _DeletePref($key, $user)
	{
		global $db;
		
		$db->Query('DELETE FROM {pre}userprefs WHERE userID=? AND `key`=?',
			$user,
			$key);
		return($db->AffectedRows() == 1);
	}

	/*
	 * OnSignup
	 */
	function OnSignup($userid, $usermail)
	{
		$this->_SetPref('checkpassword_date', time(), $userid);
	}

	/*
	* �ndern vom password
	*/
	function FileHandler($file, $action)
	{
		global $thisUser, $db, $tpl, $userRow, $lang_custom, $lang_user;

		// beim Speichern des neuen Passwortes
		if($file=='prefs.php' && $action =="membership" && $_REQUEST['do']=='changePW')
		{
			if($this->_GetPref('checkpassword_date', $thisUser->_id) != -1)
			{
				$thisUser->SetPref('checkpassword_date', time());
			}
		}

		// speichert das neue passwort
		if($file=='index.php' && $action =="changepassword" && isset($_REQUEST['do']) && $_REQUEST['do'] == 'changePW' && IsPOSTRequest())
		{
			RequestPrivileges(PRIVILEGES_USER);
			// password
			$suPass1 = CharsetDecode($_POST['pass1'], false, 'ISO-8859-15');
			$suPass2 = CharsetDecode($_POST['pass2'], false, 'ISO-8859-15');
			
			$res = $db->Query('SELECT gruppe, passwort, passwort_salt FROM {pre}users WHERE id=?',
				(int) $thisUser->_id);
			$user = $res->FetchArray();
			$res->Free();

			$res = $db->Query('SELECT time, duty, secure FROM {pre}plugin_checkpassword_groups WHERE groups=?',
				(int) $user['gruppe']);
			$row = $res->FetchArray();
			$res->Free();
			
			$suPass3 = md5(md5($suPass1).$userRow['passwort_salt']);
			if(strlen($suPass1) < 3 || $suPass1 != $suPass2 || ($row['duty'] == 1 AND $user['passwort'] == $suPass3))
			{
				$tpl->assign('errorStep', true);
				$tpl->assign('errorInfo', $lang_user['pwerror']);
			} else if($row['secure'] == 1 AND !$this->passwordSecure($suPass1)) {
				$tpl->assign('errorStep', true);
				$tpl->assign('errorInfo', $lang_user['checkpassword_strongpassword']);
			} else {
				$userRow['passwort'] = md5(md5($suPass1).$userRow['passwort_salt']);
				$thisUser->UpdateContactData($userRow, false, true, 0, $suPass1);

				if($this->_GetPref('checkpassword_date', $thisUser->_id) != -1)
				{
					$thisUser->SetPref('checkpassword_date', time());
				}

				// delete cookies
				setcookie('bm_savedUser', 		'',		 		time() - TIME_ONE_HOUR);
				setcookie('bm_savedPassword', 	'',		 		time() - TIME_ONE_HOUR);
				setcookie('bm_savedLanguage', 	'',		 		time() - TIME_ONE_HOUR);
				
				header('Location: start.php?sid=' . session_id());
				exit();
			}

			$tpl->assign('pageTitle', $lang_user['changepw']);
			$tpl->assign('title', $lang_user['changepw']);
			$tpl->assign('msg', $lang_custom['checkpassword_login_text']);
			$tpl->assign('backlink', 'start.php?sid=' . session_id());
			$tpl->assign('sid', session_id());
			$tpl->assign('duty', $row['duty']);
			$tpl->assign('languageList', GetAvailableLanguages());

			$tpl->assign('page', $this->_templatePath('checkpassword.login.tpl'));
			$tpl->display('nli/index.tpl');
			exit();
		}
	}

	/*
	 * OnDeleteUser
	 */
	function OnDeleteUser($userId)
	{
		$this->_DeletePref('checkpassword_date', $userId);
    }

	/*
	* OnLogin
	*/
    function OnLogin($userID, $interface = 'web')
    {
        global $db, $tpl, $lang_custom, $lang_user;

		if(strpos($_SERVER['REQUEST_URI'], 'httpmail') !== false)
		{
			return;
		}

		if($this->_GetPref('checkpassword_date', $userID) == false)
		{
			$this->_SetPref('checkpassword_date', time(), $userID);
		}

		$res = $db->Query('SELECT gruppe FROM {pre}users WHERE id=?',
			(int) $userID);
		$user = $res->FetchArray();
		$res->Free();

		$res = $db->Query('SELECT time, duty FROM {pre}plugin_checkpassword_groups WHERE groups=?',
			(int) $user['gruppe']);

		if($res->RowCount() > 0)
		{
			if($this->_GetPref('checkpassword_date', $userID) != -1)
			{
				$row = $res->FetchArray();
				$res->Free();

				if(($this->_GetPref('checkpassword_date', $userID)+$row['time']) <= time())
				{
					$tpl->assign('pageTitle', $lang_user['changepw']);
					$tpl->assign('title', $lang_user['changepw']);
					$tpl->assign('msg', $lang_custom['checkpassword_login_text']);
					$tpl->assign('backlink', 'start.php?sid=' . session_id());
					$tpl->assign('sid', session_id());
					$tpl->assign('duty', $row['duty']);
					$tpl->assign('languageList', GetAvailableLanguages());

					$tpl->assign('page', $this->_templatePath('checkpassword.login.tpl'));
					$tpl->display('nli/index.tpl');
					exit();
				}
			}
		}
    }

	function passwordSecure($pw)
	{
		$value = $differentChars = $numbers = $alpha = $others = 0;
		$pwLength = strlen($pw);
	
		for($i=0; $i<$pwLength; $i++)
		{
			$c = substr($pw, $i, 1);
			
			if(is_numeric($c)) {
				$numbers++;
			} else if(preg_match("/[A-Z]/i",strtolower($c))) {
				$alpha++;
			} else {
				$others++;
			}
			
			$unique = true;
			
			for($j=$i; $j<$pwLength; $j++)
			{
				$d = substr($pw, $j, 1);
				
				if(($d == $c) && ($j != $i))
					$unique = false;
			}
			
			if($unique)
				$differentChars++;
		}
	
		$pwLength = $differentChars;
		$value  = ($pwLength / 8) * 100;
		if($numbers == $pwLength)
			$value *= 0.5;
		$value += $others * 18;

		if($pwLength < 4)
			return false;
		
		if($value >= 100)
			return true;
		if($value < 0)
			return false;
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
$plugins->registerPlugin('checkpassword');
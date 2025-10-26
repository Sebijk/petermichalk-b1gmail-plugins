<?php
declare(strict_types=1);

/**
 * smsTAN Plugin
 * 
 * Enables SMS-based two-factor authentication for b1gMail.
 * Users can receive TAN codes via SMS for secure login.
 * 
 * @version 1.3.0
 * @since PHP 8.3
 * @license GPL
 */
class smstan extends BMPlugin 
{
	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 = 'page1';
	private const ADMIN_PAGE2 = 'page2';

	/**
	 * Link automatisch anzeigen
	 */
	public const PLUGIN_LINK_ENABLED = true;

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
		$this->pluginName 			= 'smsTAN';
		$this->pluginVersion 		= '1.3.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages 			= true;
		$this->admin_page_title 	= $this->pluginName;
		$this->admin_page_icon 		= "smstan.logo.16.png";

		$this->RegisterGroupOption('smstan_plugin', FIELD_CHECKBOX, 'smsTAN?');
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
				'title' => $lang_admin['common'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active' => $action === self::ADMIN_PAGE1,
				'icon' => '../plugins/templates/images/smstan.logo.png'
			],
			1 => [
				'title' => $lang_admin['lastlogin'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active' => $action === self::ADMIN_PAGE2,
				'icon' => '../plugins/templates/images/smstan.logo.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('smstan.page1.acp.tpl'));
			$this->_Page1();
		} elseif($action === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('smstan.page2.acp.tpl'));
			$this->_Page2();
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
		$lang_user['smstan'] 		= $lang_admin['smstan_name']= 'smsTAN';
		$lang_user['smstan_text']	= $lang_admin['smstan_text']= 'Ihnen wird per SMS ein Bestätigungscode auf Ihr Mobiltelefon gesendet. Die Anmeldung muss anschlie&szlig;end mit diesem Bestätigungscode best&auml;tigt werden.';
		$lang_user['smstan_text2']								= 'Bitte geben Sie im unteren Feld Ihren Bestätigungscode ein.';
		$lang_user['smstan_tan']								= 'Bestätigungscode';
		$lang_user['smstan_requesttan']							= 'Anfordern';
		$lang_user['smstan_codeexist']							= 'Bitte haben Sie daf&uuml;r Verst&auml;tndnis, dass eine TAN aus Sicherheitsgr&uuml;nden nur einmal innerhalb von 15 Minuten angefordert werden kann.';
		$lang_user['smstan_grouperror']							= 'Sie sind nicht berechtigt, dass smsTAN Verfahren zu nutzen.';
		$lang_user['smstan_badcode']							= 'Die angegebene TAN stimmt nicht mit der in unseren Unterlagen hinterlegten TAN &uuml;berein. Bitte versuchen Sie es erneut.';
		$lang_user['smstan_priceerror']							= 'Bedauerlicherweise weist Ihr Account nicht gen&uuml;gend Credits auf, um Ihnen das Passwort per SMS zu senden. Bitte wenden Sie sich direkt an den Support!';
		$lang_user['smstan_nosms_nummer']						= 'Leider ist in Ihrem Account keine Handy Nummer hinterlegt. Tragen Sie diese bitte ein, um das smsTAN Verfahren nutzen zu k&ouml;nnen.';
		$lang_custom['smstan_sms_text'] 						= "Ihr neuer Bestätigungscode für %%user%% lautet: %%code%%";
		$lang_admin['text_smstan_sms_text'] 					= $this->name . ': SMS Text';
		$lang_admin['smstan_abbuchen']							= 'SMS vom Benutzer abbuchen';

		$lang_user['smstan_free']								= 'Ihnen entstehen durch die Nutzung dieses Services keine Kosten. Der Versand des Bestätigungscode ist kostenlos.';
		$lang_user['smstan_price']								= 'Der Versand der TAN mittels SMS wird mit %%credits%% Credits abgerechnet.';

		$lang_user['smstan_allprice']							= 'Ihnen wurden innerhalb der letzten 30 Tage f&uuml;r diesen Dienst %%credits%% Credits abgebucht.';
		$lang_user['smstan']									= 'smsTAN';
		$lang_user['prefs_d_smstan']							= 'Ihnen wird, wie beim Online Banking, per SMS eine TAN auf Ihr Mobiltelefon gesendet.';
		$lang_user['ip']										= $lang_admin['ip'];
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
		global $db;

		$db->Query('Create TABLE IF NOT EXISTS {pre}mod_smstan_keys (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`userid` INT(11) NOT NULL,
			`code` VARCHAR(1000) NOT NULL,
			`time` INT(11) NOT NULL,
			PRIMARY KEY (`id`))');

		$db->Query('Create TABLE IF NOT EXISTS {pre}mod_smstan_log (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`userid` INT(11) NOT NULL,
			`IP` VARCHAR(40) NOT NULL,
			`time` INT(11) NOT NULL,
			`credits` INT(3) NOT NULL,
			PRIMARY KEY (`id`))');

		$db->Query('Create TABLE IF NOT EXISTS {pre}mod_smstan_banip (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`IP` VARCHAR(40) NOT NULL,
			`time` INT(11) NOT NULL,
			PRIMARY KEY (`id`))');

		$this->_setPref("fromno", "");
		$this->_setPref("smstype", 0);
		$this->_setPref("chargefromuser", 1);
			
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully installed.', PRIO_PLUGIN, __FILE__, __LINE__);
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
		global $db;

		$db->Query('DROP TABLE {pre}mod_smstan_keys');
		$db->Query('DROP TABLE {pre}mod_smstan_log');
		$db->Query('DROP TABLE {pre}mod_smstan_banip');

		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return(true);
    }

	/**
	 * Admin page 1: General settings
	 * 
	 * Handles the main configuration page for the smsTAN plugin.
	 * Processes form submissions and displays current settings.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		// Einstellungen speichern
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
			$fromno = isset($_REQUEST['fromno']) ? trim($_REQUEST['fromno']) : '';
			$smstype = isset($_REQUEST['smstype']) ? (int)$_REQUEST['smstype'] : 0;
			$chargefromuser = isset($_REQUEST['chargefromuser']) ? 1 : 0;
			
			$this->_setPref("fromno", $fromno);
			$this->_setPref("smstype", $smstype);
			$this->_setPref("chargefromuser", $chargefromuser);
		}
		
		$res = $db->Query('SELECT COUNT(ip) AS c FROM {pre}mod_smstan_banip');
		$row = $res->FetchArray(MYSQLI_ASSOC);
		$res->Free();

		$tpl->assign('banips', 			$row['c']);
		$tpl->assign('fromno', 			$this->_getPref("fromno"));
		$tpl->assign('allsmstype',		$this->GetSMSTypes());
		$tpl->assign('smstype',			$this->_getPref("smstype"));
		$tpl->assign('chargefromuser', 	$this->_getPref("chargefromuser"));
	}

	/**
	 * Admin page 2: Log display
	 * 
	 * Shows the SMS TAN usage log with recent entries.
	 * Limited to 100 entries for performance reasons.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 */
	private function _Page2(): void
	{
		global $tpl, $db;
		
		$logs = [];
		$res = $db->Query('SELECT * FROM {pre}mod_smstan_log ORDER BY time DESC LIMIT 100');
		if ($res) {
			while ($row = $res->FetchArray()) {
				$logs[] = $row;
			}
			$res->Free();
		}
		$tpl->assign('logs', $logs);
	}

	/**
	 * Cron job handler
	 * 
	 * Performs regular cleanup tasks:
	 * - Removes expired TAN codes
	 * - Cleans old log entries (30+ days)
	 * - Removes expired IP bans
	 * 
	 * @return void
	 * @global object $db Database connection
	 */
	public function OnCron(): void
	{
		global $db;
		
		// Abgelaufene TAN-Codes löschen
		$db->Query('DELETE FROM {pre}mod_smstan_keys WHERE time < UNIX_TIMESTAMP()');
		
		// Alte Log-Einträge löschen (30 Tage)
		$db->Query('DELETE FROM {pre}mod_smstan_log WHERE time < (UNIX_TIMESTAMP() - (30*24*60*60))');
		
		// Abgelaufene IP-Sperren löschen
		$db->Query('DELETE FROM {pre}mod_smstan_banip WHERE time < UNIX_TIMESTAMP()');
	}

	/**
	 * Get user pages for navigation
	 * 
	 * Returns navigation pages for non-logged-in users.
	 * Only shows smsTAN login option when plugin link is enabled.
	 * 
	 * @param bool $loggedin Whether user is currently logged in
	 * @return array Array of navigation pages
	 * @global array $lang_user User language variables
	 */
	public function getUserPages($loggedin): array
	{
		global $lang_user;

		if ($loggedin) {
			return [];
		}

		if (self::PLUGIN_LINK_ENABLED) {
			return [
				'smstan' => [
					'text' => $lang_user['smstan'],
					'link' => 'index.php?action=smstan'
				]
			];
		}
		
		return [];
	}

	/**
	 * User preferences page handler
	 * 
	 * Handles user-specific settings for the smsTAN plugin.
	 * Allows users to view their SMS log and enable/disable the service.
	 * 
	 * @param string $action The action to perform
	 * @return bool Whether the action was handled
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $userRow Current user data
	 * @global object $thisUser Current user object
	 * @global array $lang_user User language variables
	 */
	public function UserPrefsPageHandler($action): bool
	{
		global $tpl, $db, $userRow, $thisUser, $lang_user;

		if ($action == "smstan") {
			// Log-Eintrag löschen
			if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'del') {
				$logId = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
				if ($logId > 0) {
					$db->Query('DELETE FROM {pre}mod_smstan_log WHERE id=? AND userid=?', 
						$logId,
						(int)$userRow["id"]);
				}
			}
			
			// Einstellungen speichern
			if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
				$smstan_allow = isset($_REQUEST['smstan_allow']) ? 1 : 0;
				$thisUser->SetPref("smstan_disable", !$smstan_allow);
			}

			$logs = [];
			$res = $db->Query('SELECT * FROM {pre}mod_smstan_log WHERE userid=? ORDER BY time DESC LIMIT 10',
				(int)$userRow["id"]);
			if ($res) {
				while ($row = $res->FetchArray()) {
					$logs[] = $row;
				}
				$res->Free();
			}
			
			$pricetext		= $lang_user['smstan_allprice'];
			$preis			= $this->GetSumPaidCredits($userRow["id"]);
			$preis			= isset($preis) ? $preis : 0;
			$pricetext 		= str_replace('%%credits%%', $preis, $pricetext);

			$tpl->assign('pricetext', 		$pricetext);
			$tpl->assign('chargefromuser', 	$this->_getPref("chargefromuser"));
			
			$tpl->assign('logs', 			$logs);
			$tpl->assign('smstan_allow', 	!$thisUser->GetPref("smstan_disable"));

			$tpl->assign('pageContent', $this->_templatePath('smstan.prefspage.tpl'));
			$tpl->display('li/index.tpl');
			return(true);
		}
		return(false);
	}

	/**
	 * File handler for page processing
	 * 
	 * Main handler for processing login requests and displaying pages.
	 * Handles both SMS sending and TAN code validation.
	 * 
	 * @param string $file The file being processed
	 * @param string $action The action being performed
	 * @return bool|void Whether the file was handled
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $bm_prefs b1gMail preferences
	 * @global array $lang_user User language variables
	 * @global array $lang_custom Custom language variables
	 * @global array $prefsItems Preferences items
	 * @global array $prefsImages Preferences images
	 * @global array $prefsIcons Preferences icons
	 * @global array $groupRow Current group data
	 */
	public function FileHandler($file, $action)
	{
		global $tpl, $db, $bm_prefs, $lang_user, $lang_custom, $prefsItems, $prefsImages, $prefsIcons, $groupRow;

		if ($file == 'prefs.php') {
			if (!$this->GetGroupOptionValue('smstan_plugin', $groupRow['id'])) {
				return false;
			}

			$prefsItems['smstan'] = true;
			$prefsImages['smstan'] = './plugins/templates/images/smstan.logo.48.png';
			$prefsIcons['smstan'] = './plugins/templates/images/smstan.logo.16.png';
		}

		if ($file == 'index.php' && $action == 'smstan') {
			// SMS senden
			if (isset($_REQUEST['do']) && $_REQUEST['do'] == "send") {
				if (!class_exists('BMSMS')) {
					include(B1GMAIL_DIR . 'serverlib/sms.class.php');
				}

				// E-Mail validieren
				$email = '';
				if (isset($_REQUEST['email_full']) && !empty($_REQUEST['email_full'])) {
					$email = trim($_REQUEST['email_full']);
				} elseif (isset($_REQUEST['email_local']) && isset($_REQUEST['email_domain'])) {
					$email_local = trim($_REQUEST['email_local']);
					$email_domain = trim($_REQUEST['email_domain']);
					if (!empty($email_local) && !empty($email_domain)) {
						$email = $email_local . '@' . $email_domain;
					}
				}
				
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$this->_badlogin($lang_user['baduser']);
				}
				
				$userID = BMUser::GetID($email);
				$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

				if($this->isIpBan($ip)) {
					$this->_badlogin($lang_user['smstan_banip']);
				}
				if(!$userID) {
					$this->_badlogin($lang_user['baduser']);
				} else if($this->CodeExist($userID)) {
					$this->_badlogin($lang_user['smstan_codeexist']);
				}

				$thisUser 	= _new('BMUser', array($userID));
				$userRow	= $thisUser->_row;
				$group		= $thisUser->GetGroup();
				$toNo		= $userRow['mail2sms_nummer'];

				if($toNo == "") {
					$this->_badlogin($lang_user['smstan_nosms_nummer']);
				}
				if($thisUser->GetPref("smstan_disable")) {
					$this->_badlogin($lang_user['smstan_grouperror']);
				}

				if(!$this->GetGroupOptionValue('smstan_plugin', $group->_id)) {
					$this->_badlogin($lang_user['smstan_grouperror']);
				}

				$smsTyp 	= $this->_getPref("smstype");
				$preis		= $this->GetPrice($smsTyp);
				if($this->_getPref("chargefromuser")) {
					$userMoney	= $thisUser->GetBalance();

					if($userMoney < $preis)
					{
						$this->_badlogin($lang_user['smstan_priceerror']);
					}
				}

				$sms 		= _new('BMSMS', array($userID, &$thisUser));
				$codeID 	= $this->RequestCode($userID);
				$code 		= $this->GetCode($codeID);

				$text		= $this->_parse($lang_custom['smstan_sms_text'], $userRow, $code);
				$fromNo		= $this->_getPref("fromno");

				$result = $sms->Send($fromNo, $toNo, $text, $this->_getPref("smstype"), $this->_getPref("chargefromuser"), false);
				PutLog('smsTAN Sent SMS Key <'.$code.'> to <'.$toNo.'>', PRIO_PLUGIN, __FILE__, __LINE__);
				if($result)
				{
					$this->RequestBan($ip);
					$this->LogRequest($userID, $ip, $preis);

					$tpl->assign('pageTitle', $lang_user['smstan']." ".$lang_user['login']);
					$tpl->assign('languageList', GetAvailableLanguages());
					$tpl->assign('page', $this->_templatePath('smstan.login2.tpl'));
					$tpl->display('nli/index.tpl');
					exit();
				} else {
					$this->_badlogin($lang_user['smssendfailed']);
				}
			} elseif (isset($_REQUEST['do']) && $_REQUEST['do'] == "smslogin") {
				// TAN-Code validieren
				$code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
				if (empty($code) || strlen($code) > 20) {
					$this->_badlogin($lang_user['smstan_badcode']);
				}
				
				$userID = $this->GetUserFromCode($code);
				$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

				if($this->isIpBan($ip)) {
					$this->_badlogin($lang_user['smstan_banip']);
				}
				if(!$userID) {
					$this->RequestBan($ip);
					$this->_badlogin($lang_user['smstan_badcode']);
				}
				$codeID 	= $this->CodeExist($userID);
				if(!$codeID || ($this->GetCode($codeID) != $code)) {
					$this->RequestBan($ip);
					$this->_badlogin($lang_user['smstan_badcode']);
				}
				$this->ReleaseCode($codeID);

				$res = $db->Query('SELECT email, passwort FROM {pre}users Where id=? Limit 1', 
					(int) $userID);
				$codeuser = $res->FetchArray();
				$res->Free();

				list($result, $param) = BMUser::Login($codeuser['email'], $codeuser['passwort'], true, true, "", true);
				// login ok?
				if($result == USER_OK)
				{
					// stats
					Add2Stat('login');
					PutLog('smsTAN Login OK ID ('.$userID.')', PRIO_PLUGIN, __FILE__, __LINE__);
					// delete cookies
					setcookie('bm_savedUser', 		'', 			time() - TIME_ONE_HOUR);
					setcookie('bm_savedPassword', 	'', 			time() - TIME_ONE_HOUR);
					setcookie('bm_savedLanguage', 	'', 			time() - TIME_ONE_HOUR);
					setcookie('bm_savedSSL', 		'', 			time() - TIME_ONE_HOUR);
					// register language
					$_SESSION['bm_sessionLanguage'] = $bm_prefs['language'];

					header('Location: start.php?sid=' . $param);
					exit();
				} else {
					// sms validation input?
					if($result == USER_LOCKED && $requiresValidation)
					{
						$tpl->assign('email',		$email);
						$tpl->assign('password',	strlen($password) == 32 ? $password : md5($password));
						$tpl->assign('savelogin',	isset($_POST['savelogin']));
						$tpl->assign('language',	$language);
						$tpl->assign('page',		'nli/login.smsvalidation.tpl');
					} else {
						// tell user what happened
						switch($result)
						{
						case USER_BAD_PASSWORD:
							$tpl->assign('msg',	sprintf($lang_user['badlogin'], $param));
							break;
						case USER_DOES_NOT_EXIST:
							$tpl->assign('msg', $lang_user['baduser']);
							break;
						case USER_LOCKED:
							$tpl->assign('msg', $lang_user['userlocked']);
							break;
						case USER_LOGIN_BLOCK:
							$tpl->assign('msg', sprintf($lang_user['loginblocked'], FormatDate($param)));
							break;
						}
						$tpl->assign('page',	'nli/loginresult.tpl');
					}
					$tpl->assign('languageList', GetAvailableLanguages());
					$tpl->display('nli/index.tpl');
					exit();
				}
			} else {
				$pricetext;
				if($this->_getPref("chargefromuser")) {
					$pricetext		= $lang_user['smstan_price'];

					$smsTyp 	= $this->_getPref("smstype");
					$preis		= $this->GetPrice($smsTyp);

					$pricetext 		= str_replace('%%credits%%', $preis, $pricetext);
				} else {
					$pricetext		= $lang_user['smstan_free'];
				}

				$tpl->assign('pricetext',			$pricetext);
				$tpl->assign('pageTitle',			$lang_user['smstan']." ".$lang_user['login']);
				$tpl->assign('domain_combobox',		$bm_prefs['domain_combobox'] == 'yes');
				$tpl->assign('domainList', 			GetDomainList('login'));
				$tpl->assign('languageList', 		GetAvailableLanguages());
				$tpl->assign('page', 				$this->_templatePath('smstan.login.tpl'));
				$tpl->display('nli/index.tpl');
				exit();
			}
		}
	}

	/**
	 * Display error message for failed login
	 * 
	 * Shows an error message and terminates script execution.
	 * Sanitizes the message to prevent XSS attacks.
	 * 
	 * @param string $msg Error message to display
	 * @return never This method never returns (calls exit)
	 * @global object $tpl Template engine
	 * @global array $lang_user User language variables
	 */
	private function _badlogin(string $msg): never
	{
		global $tpl, $lang_user;
		
		$msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
		
		$tpl->assign('pageTitle', $lang_user['smstan'] . " " . $lang_user['login']);
		$tpl->assign('languageList', GetAvailableLanguages());
		$tpl->assign('msg', $msg);
		$tpl->assign('page', 'nli/loginresult.tpl');
		$tpl->display('nli/index.tpl');
		exit();
	}

	/**
	 * Create new TAN code for user
	 * 
	 * Generates a new TAN code and stores it in the database
	 * with a 15-minute expiration time.
	 * 
	 * @param int $userid User ID
	 * @return int|false Insert ID on success, false on failure
	 * @global object $db Database connection
	 */
	private function RequestCode(int $userid): int|false
	{
		global $db;
		
		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}
		
		$code = $this->CodeGen(8);
		$expiry = time() + (15 * 60); // 15 Minuten
		
		$db->Query('INSERT INTO {pre}mod_smstan_keys(userid, code, time) VALUES(?, ?, ?)',
			$userid,
			$code,
			$expiry);
		
		return $db->InsertId();
	}

	/**
	 * Invalidate used TAN code
	 * 
	 * Marks a TAN code as used by clearing it from the database.
	 * 
	 * @param int $id Code ID to invalidate
	 * @return void
	 * @global object $db Database connection
	 */
	private function ReleaseCode(int $id): void
	{
		global $db;
		
		$id = (int)$id;
		if ($id > 0) {
			$db->Query('UPDATE {pre}mod_smstan_keys SET code="" WHERE id=?', $id);
		}
	}

	/**
	 * Generate random TAN code
	 * 
	 * Creates a random code with alternating vowels and consonants
	 * for better readability and pronunciation.
	 * 
	 * @param int $chars Number of characters to generate (max 20)
	 * @return string Generated TAN code
	 */
	private function CodeGen(int $chars): string
	{
		$chars = (int)$chars;
		if ($chars <= 0 || $chars > 20) {
			$chars = 8;
		}
		
		$vocals = 'aeiouAEIOU1234567890';
		$cons = 'bcdfghjklmnpqrstvwxzBCDFGHJKLMNPQRSTVWXZ1234567890';
		$result = '';
		
		for ($i = 0; $i < $chars; $i++) {
			if ($i % 2 == 0) {
				$result .= $vocals[mt_rand(0, strlen($vocals) - 1)];
			} else {
				$result .= $cons[mt_rand(0, strlen($cons) - 1)];
			}
		}
		
		return $result;
	}

	/**
	 * Get TAN code by ID
	 * 
	 * Retrieves a TAN code from the database if it's still valid.
	 * 
	 * @param int $id Code ID
	 * @return string|false TAN code on success, false if not found/expired
	 * @global object $db Database connection
	 */
	private function GetCode(int $id): string|false
	{
		global $db;
		
		$id = (int)$id;
		if ($id <= 0) {
			return false;
		}
		
		$res = $db->Query('SELECT code FROM {pre}mod_smstan_keys WHERE id=? AND time > UNIX_TIMESTAMP()', $id);
		if ($res && $res->RowCount() > 0) {
			$row = $res->FetchArray();
			$res->Free();
			return $row['code'];
		}
		if ($res) $res->Free();
		return false;
	}
	
	/**
	 * Get user ID from TAN code
	 * 
	 * Looks up which user a TAN code belongs to.
	 * 
	 * @param string $code TAN code
	 * @return int|false User ID on success, false if code invalid/expired
	 * @global object $db Database connection
	 */
	private function GetUserFromCode(string $code): int|false
	{
		global $db;
		
		if (empty($code)) {
			return false;
		}
		
		$res = $db->Query('SELECT userid FROM {pre}mod_smstan_keys WHERE code=? AND time > UNIX_TIMESTAMP()', $code);
		if ($res && $res->RowCount() > 0) {
			$row = $res->FetchArray();
			$res->Free();
			return (int)$row['userid'];
		}
		if ($res) $res->Free();
		return false;
	}

	/**
	 * Check if valid TAN code exists for user
	 * 
	 * Determines if a user already has an active TAN code.
	 * 
	 * @param int $userid User ID
	 * @return int|false Code ID if exists, false otherwise
	 * @global object $db Database connection
	 */
	private function CodeExist(int $userid): int|false
	{
		global $db;
		
		$userid = (int)$userid;
		if ($userid <= 0) {
			return false;
		}

		$res = $db->Query('SELECT id FROM {pre}mod_smstan_keys WHERE userid=? AND time > UNIX_TIMESTAMP()', $userid);
		if ($res && $res->RowCount() > 0) {
			$row = $res->FetchArray(MYSQLI_NUM);
			$res->Free();
			return (int)$row[0];
		}
		if ($res) $res->Free();
		return false;
	}

	/**
	 * Parse SMS text template
	 * 
	 * Replaces placeholders in SMS text with actual user data and TAN code.
	 * 
	 * @param string $text Template text
	 * @param array $userRow User data array
	 * @param string $code TAN code
	 * @return string Parsed text with replaced placeholders
	 * @global array $bm_prefs b1gMail preferences
	 */
	private function _parse(string $text, array $userRow, string $code): string
	{
		global $bm_prefs;
		
		$text = (string)$text;
		$code = (string)$code;
		$email = isset($userRow['email']) ? (string)$userRow['email'] : '';
		
		$replacements = [
			'%%user%%' => $email,
			'%%wddomain%%' => str_replace('@', '.', $email),
			'%%selfurl%%' => isset($bm_prefs['selfurl']) ? $bm_prefs['selfurl'] : '',
			'%%hostname%%' => isset($bm_prefs['b1gmta_host']) ? $bm_prefs['b1gmta_host'] : '',
			'%%projecttitle%%' => isset($bm_prefs['titel']) ? $bm_prefs['titel'] : '',
			'%%code%%' => $code
		];
		
		return str_replace(array_keys($replacements), array_values($replacements), $text);
	}

	/**
	 * Get available SMS types
	 * 
	 * Retrieves all configured SMS types from the database.
	 * 
	 * @return array Array of SMS types with ID and title
	 * @global object $db Database connection
	 */
	private function GetSMSTypes(): array
	{
		global $db;

		$result = [];
		$res = $db->Query('SELECT id, titel FROM {pre}smstypen ORDER BY titel ASC');
		if ($res) {
			while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
				$result[(int)$row['id']] = [
					'id' => (int)$row['id'],
					'title' => htmlspecialchars($row['titel'], ENT_QUOTES, 'UTF-8')
				];
			}
			$res->Free();
		}
		
		return $result;
	}

	/**
	 * Check if IP address is banned
	 * 
	 * Determines if an IP has exceeded the maximum number of failed attempts.
	 * 
	 * @param string $ip IP address to check
	 * @return bool True if IP is banned (more than 5 attempts)
	 * @global object $db Database connection
	 */
	private function isIpBan(string $ip): bool
	{
		global $db;
		
		if (empty($ip)) {
			return false;
		}

		$res = $db->Query('SELECT COUNT(ip) AS cip FROM {pre}mod_smstan_banip WHERE ip=? AND time > UNIX_TIMESTAMP()', $ip);
		if ($res) {
			$row = $res->FetchArray(MYSQLI_ASSOC);
			$res->Free();
			$count = (int)$row['cip'];
			return $count > 5;
		}
		return false;
	}

	/**
	 * Register failed login attempt
	 * 
	 * Records a failed login attempt for IP-based rate limiting.
	 * 
	 * @param string $ip IP address
	 * @return int|false Insert ID on success, false on failure
	 * @global object $db Database connection
	 */
	private function RequestBan(string $ip): int|false
	{
		global $db;
		
		if (empty($ip)) {
			return false;
		}
		
		$db->Query('INSERT INTO {pre}mod_smstan_banip(ip, time) VALUES(?, ?)',
			$ip,
			time() + (15 * 60)); // 15 Minuten
		
		return $db->InsertId();
	}
	
	/**
	 * Get price for SMS type
	 * 
	 * Retrieves the cost for sending an SMS of the specified type.
	 * 
	 * @param int $type SMS type ID (0 for default)
	 * @return float SMS price or 0 if not found
	 * @global object $db Database connection
	 */
	private function GetPrice(int $type): float
	{
		global $db;
		
		$type = (int)$type;
		$price = 0;
		
		if ($type == 0) {
			$sql = $db->Query("SELECT price FROM {pre}smstypen WHERE std = ?", 1);
		} else {
			$sql = $db->Query("SELECT price FROM {pre}smstypen WHERE id = ?", $type);
		}
		
		if ($sql && $sql->RowCount() > 0) {
			$row = $sql->FetchArray();
			$price = isset($row['price']) ? (float)$row['price'] : 0;
		}
		if ($sql) $sql->Free();
		
		return $price;
	}
	
	/**
	 * Log SMS request
	 * 
	 * Records an SMS request for audit and billing purposes.
	 * 
	 * @param int $userID User ID
	 * @param string $ip IP address
	 * @param float $preis SMS cost
	 * @return void
	 * @global object $db Database connection
	 */
	private function LogRequest(int $userID, string $ip, float $preis): void
	{
		global $db;
		
		$userID = (int)$userID;
		$preis = (float)$preis;
		
		if ($userID > 0) {
			$db->Query('INSERT INTO {pre}mod_smstan_log(userid, ip, time, credits) VALUES(?, ?, ?, ?)',
				$userID,
				$ip,
				time(),
				$preis);
		}
	}
	
	/**
	 * Get sum of spent credits (last 30 days)
	 * 
	 * Calculates the total credits spent by a user on SMS TAN
	 * within the last 30 days.
	 * 
	 * @param int $userID User ID
	 * @return float Total credits spent
	 * @global object $db Database connection
	 */
	private function GetSumPaidCredits(int $userID): float
	{
		global $db;
		
		$userID = (int)$userID;
		if ($userID <= 0) {
			return 0;
		}

		$res = $db->Query('SELECT SUM(credits) as paid FROM {pre}mod_smstan_log WHERE userid=? AND time > (UNIX_TIMESTAMP() - (30*24*60*60))', $userID);
		if ($res && $res->RowCount() > 0) {
			$row = $res->FetchArray(MYSQLI_ASSOC);
			$res->Free();
			return (float)($row['paid'] ?? 0);
		}
		if ($res) $res->Free();
		return 0;
	}
}

// Link automatisch anzeigen
define("PLUGIN_SMSTAN_LINK", smstan::PLUGIN_LINK_ENABLED);

/**
 * Plugin registration
 * 
 * Registers the plugin in the b1gMail plugin system.
 * This line must be at the end of the file so that the plugin
 * is recognized and loaded by b1gMail.
 * 
 * @global object $plugins b1gMail plugin manager
 */
$plugins->registerPlugin('smstan');
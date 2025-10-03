<?php
declare(strict_types=1);

/**
 * Eigene Startseiten Plugin
 * 
 * With this plugin you can create custom start pages for groups, individual users
 * or directly for all users from the admin area.
 *  
 * @version 1.3.0
 * @since PHP 8.3
 * @license GPL
 */
class eigenestartseiten extends BMPlugin 
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
		$this->pluginName 			= 'Eigene Startseiten';
		$this->pluginVersion 		= '1.3.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;	

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "eigenestartseiten_icon.png";
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
				'title'		=> $lang_admin['start'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/eigenestartseiten_logo.png'
			],
			1 => [
				'title'		=> $lang_admin['create'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active'	=> $action === self::ADMIN_PAGE2,
				'icon'		=> './templates/images/extension_add.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($_REQUEST['action'] === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('eigenestartseiten1.pref.tpl'));
			$this->_Page1();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('eigenestartseiten2.pref.tpl'));
			$this->_Page2();
		} else if($_REQUEST['action'] == 'page3') {
			$tpl->assign('page', $this->_templatePath('eigenestartseiten3.pref.tpl'));
			$this->_Page3();
		}
	}
	
	/**
	 * Language variables handler
	 * 
	 * Loads language-specific variables for the plugin.
	 * 
	 * @param array $lang_user User language variables (by reference)
	 * @param array $lang_client Client language variables (by reference)
	 * @param array $lang_custom Custom language variables (by reference)
	 * @param array $lang_admin Admin language variables (by reference)
	 * @param string $lang Language identifier
	 * @return void
	 */
	public function OnReadLang(&$lang_user, &$lang_client, &$lang_custom, &$lang_admin, $lang): void
	{
		$lang_admin['eigenestartseiten_name']			= 'Eigene Startseiten';
		$lang_admin['eigenestartseiten_text']			= 'Erstellen Sie eigene Startseiten im "Eingeloggten" oder "Nicht eingeloggten" Bereich.';

		if (strpos($lang, 'deutsch') !== false)
		{
			$lang_admin['eigenestartseiten_own']		= 'Eigene';
			$lang_admin['change']						= '&auml;ndern';
			$lang_admin['eigenestartseiten_smarty']		= 'mit Smarty parsen';
			$lang_admin['icon_modern']					= 'Icon f&uuml;r modernes Template';
			$lang_admin['icon_modern_active']			= 'Aktives Icon f&uuml;r modernes Template';
		} else {
			$lang_admin['eigenestartseiten_own']		= 'own';
			$lang_admin['change']						= 'change';
			$lang_admin['eigenestartseiten_smarty']		= 'parse with smarty';
			$lang_admin['icon_modern']					= 'icon for template modern';
			$lang_admin['icon_modern_active']			= 'active icon for template modern';
		}

		$lang_admin['start'] 							= $lang_user['start'];
	}

	/**
	 * Plugin installation routine
	 * 
	 * Creates necessary database tables and adds required preferences.
	 * 
	 * @return bool Returns true on successful installation
	 * @global object $db Database connection
	 */
	public function Install(): bool
	{
		global $db;

		// Create eigenestartseite table 
		$db->Query('CREATE TABLE IF NOT EXISTS `bm60_plugin_eigenestartseiten` (
			`id` int(11) NOT NULL auto_increment,
			`title` varchar(255) NOT NULL,
			`lang` varchar(255) NOT NULL,
			`typ` int(1) NOT NULL,
			`seite` text NOT NULL,
			PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 ;');

		$db->Query('ALTER TABLE `{pre}plugin_eigenestartseiten` ADD `smarty` INT( 1 ) NOT NULL DEFAULT "0"');

		// Add eigenestartseiten preferences to prefs table
		$db->Query('ALTER TABLE `{pre}prefs` ADD `eigenestartseiten_li` INT( 1 ) NOT NULL DEFAULT "1"');
		$db->Query('ALTER TABLE `{pre}prefs` ADD `eigenestartseiten_nli` INT( 1 ) NOT NULL DEFAULT "1"');
		$db->Query('ALTER TABLE `{pre}prefs` ADD `eigenestartseiten_icon` varchar(255) NOT NULL DEFAULT "start"');
		$db->Query('ALTER TABLE `{pre}prefs` ADD `eigenestartseiten_icon_modern` varchar(255) NOT NULL DEFAULT "start"');
		$db->Query('ALTER TABLE `{pre}prefs` ADD `eigenestartseiten_icon_modern_active` varchar(255) NOT NULL DEFAULT "start"');

		PutLog('Plugin "' . $this->name . ' - ' . $this->version . '" was successfully installed.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Plugin uninstallation routine
	 * 
	 * Removes database tables and preferences created during installation.
	 * 
	 * @return bool Returns true on successful uninstallation
	 * @global object $db Database connection
	 */
	public function Uninstall(): bool
	{
		global $db;

		// Drop table
		$db->Query('DROP TABLE {pre}plugin_eigenestartseiten');

		$db->Query('ALTER TABLE `{pre}prefs`
			DROP `eigenestartseiten_li`,
			DROP `eigenestartseiten_nli`,
			DROP `eigenestartseiten_icon`,
			DROP `eigenestartseiten_icon_modern`,
			DROP `eigenestartseiten_icon_modern_active`');

		PutLog('Plugin "' . $this->name . ' - ' . $this->version . '" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/*
	*  Abfragen aller Seiten , loeschen einzelener
	*/
	function _Page1()
	{
		global $tpl, $db;
		// delete 
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete')
		{
			//loeschen der seite
			$db->Query('Delete FROM {pre}plugin_eigenestartseiten Where id=?', 
				(int) $_REQUEST['id']);
		}

		// eigeneseiten abfragen und auf Page1 ausgeben
		$eigenestartseiten = array();
		$res = $db->Query('SELECT * FROM {pre}plugin_eigenestartseiten ORDER by id ASC');
		while($row = $res->FetchArray())
		{
			$lang = $row['lang'];
			if($row['lang'] == ":all:") {
				$lang = "Alle";
			}

			$eigenestartseiten[$row['id']] = array(
				'id'					=> $row['id'],
				'title'					=> $row['title'],
				'lang'					=> $lang,
				'typ'					=> $row['typ'],
			);
		}
		$res->Free();

		$tpl->assign('eigenestartseiten', $eigenestartseiten);
	}

	/*
	*  Eintragen neuer oder bearbeiten alter Seiten
	*/
	function _Page2()
	{
		global $tpl, $db, $bm_prefs;

		// neue Seite hinzufuegen
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			// DB INSEERT
			$res = $db->Query('INSERT INTO {pre}plugin_eigenestartseiten(title, lang, typ, seite, smarty) VALUES(?,?,?,?,?)', 
				$_REQUEST['title'],
				$_REQUEST['lang'],
				$_REQUEST['typ'],
				$_REQUEST['text'],
				(int) isset($_REQUEST['smartyparsen']) ? 1 : 0);
		}

		// Seite updaten
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'update')
		{
			// DB UPDATE
			$db->Query('UPDATE {pre}plugin_eigenestartseiten SET title=?,lang=?,typ=?,seite=?,smarty=? WHERE id=?',
				$_REQUEST['title'],
				$_REQUEST['lang'],
				$_REQUEST['typ'],
				$_REQUEST['text'],
				(int) isset($_REQUEST['smartyparsen']) ? 1 : 0,
				(int) $_REQUEST['id']);
		}

		//Seiten daten fuer update abrufen
		if(isset($_REQUEST['id']))
		{
			// Seiten daten
			$res = $db->Query('SELECT * FROM {pre}plugin_eigenestartseiten WHERE id=?', 
				(int) $_REQUEST['id']);
			$eigenestartseiten = $res->FetchArray();
			$res->Free();

			$tpl->assign('eigenestartseiten', $eigenestartseiten);
			$tpl->assign('id', true);
		} else {
			$tpl->assign('id', false);
		}

		// alle sprachen
		$tpl->assign('languages', GetAvailableLanguages());
		// variable fuer den editor
		$tpl->assign('usertpldir', B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/');
	}

	/*
	*  Einstellungen speichern
	*/
	function _Page3()
	{
		global $tpl, $db, $bm_prefs;

		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			$db->Query('UPDATE {pre}prefs SET eigenestartseiten_li=?, eigenestartseiten_nli=?, eigenestartseiten_icon=?, eigenestartseiten_icon_modern=?, eigenestartseiten_icon_modern_active=? WHERE id=?',
				(int) isset($_REQUEST['change_li']) ? 1 : 0,
				(int) isset($_REQUEST['change_nli']) ? 1 : 0,
				$_REQUEST['icon'],
				$_REQUEST['icon_modern'],
				$_REQUEST['icon_modern_active'],
				(int) 1);	
		}

		$res = $db->Query('SELECT eigenestartseiten_li, eigenestartseiten_nli, eigenestartseiten_icon, eigenestartseiten_icon_modern, eigenestartseiten_icon_modern_active FROM {pre}prefs LIMIT 1');
		$eigenestartseiten = $res->FetchArray();
		$res->Free();

		// Pfade fuer icon
		$d1 = dir(B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/images/li/');
		// array fuer icon,
		$array_icon = array();

		// array icon fuellen
		$i = 0;
		while (false !== ($entry = $d1->read())) {
			if (preg_match('/tab_ico_/',$entry) AND preg_match('/.png/',$entry))
			{
				$entry_name = $entry;
				$entry_name = str_replace("tab_ico_", "", $entry_name);
				$entry_name = str_replace(".png", "", $entry_name);

				$array_icon[$i] = array(
					'small_name'		=> $entry_name,
					'full_name'			=> $entry,
				);
				$i++;
			}  
		}
		$d1->close();

		$tpl->assign('array_icon', $array_icon);
		$tpl->assign('eigenestartseiten', $eigenestartseiten);
	}

	/*
	*  Links auf der Seite anzeigen
	*/
	function getUserPages($loggedin)
	{
		global $db, $lang_user;

		// links array
		$links = array();

		if($loggedin)
		{
			$res = $db->Query('SELECT eigenestartseiten_li, eigenestartseiten_icon, eigenestartseiten_icon_modern, eigenestartseiten_icon_modern_active FROM {pre}prefs LIMIT 1');
			$prefs = $res->FetchArray();
			$res->Free();

			if($prefs['eigenestartseiten_li'] == 1)
			{
				// array fuellen
				$link = 'start.php?action=start&sid=';
				$links['plugin_startpage_li'] = array(
					'link'					=> $link,
					'text'					=> $lang_user['overview'],
					'icon'					=> $prefs['eigenestartseiten_icon'],
					'icon_modern'			=> $prefs['eigenestartseiten_icon_modern'],
					'icon_modern_active'	=> $prefs['eigenestartseiten_icon_modern_active'],
					'order'					=> 101
				);
			}
		} else {
			$res = $db->Query('SELECT eigenestartseiten_nli FROM {pre}prefs LIMIT 1');
			$prefs = $res->FetchArray();
			$res->Free();

			if($prefs['eigenestartseiten_nli'] == 1)
			{
				// array fuellen
				$link = 'index.php?action=login';
				$links['plugin_startpage_nli'] = array(
					'link'        => $link,
					'text'        => $lang_user['login']
				);
			}
		}
		return $links;
	}

	/*
	*  Anzeigen der Seiten
	*/
	function FileHandler($file, $action)
	{
		global $tpl, $db, $currentLanguage, $thisUser, $bm_prefs;

		// nicht eingeloggte Seiten
		if($file=='index.php' && $action =="" && !isset($_REQUEST['do']))
		{
			if (!preg_match('/faxPluginStatusPush/',$_SERVER['REQUEST_URI']))
			{
				$res = $db->Query('SELECT eigenestartseiten_nli FROM {pre}prefs LIMIT 1');
				$prefs = $res->FetchArray();
				$res->Free();

				if($prefs['eigenestartseiten_nli'] == 1)
				{
					// pruefen ob es eine Startseite gibt
					$res = $db->Query('SELECT * FROM {pre}plugin_eigenestartseiten WHERE lang=? AND typ=? LIMIT 1', 
						$currentLanguage,
						(int) 0);

					if($res->RowCount() == 0)
					{
						$res->Free();
						
						$res = $db->Query('SELECT * FROM {pre}plugin_eigenestartseiten WHERE lang=? AND typ=? LIMIT 1', 
							':all:',
							(int) 0);

						if($res->RowCount() == 0)
						{
							header("Location: index.php?action=login");
							exit();
						}
					}

					$startseite = $res->FetchArray();
					$res->Free();
					
					
					/**
					 * mobile redirection?
					 */
					if($bm_prefs['redirect_mobile'] == 'yes'
						&& IsMobileUserAgent()
						&& !isset($_COOKIE['noMobileRedirect']))
					{
						header('Location: ' . $bm_prefs['mobile_url']);
						exit();
					}

					// title und seite uebergeben
					$tpl->assign('pageTitle', $startseite['title']);
					$tpl->assign('text', $startseite['seite']);
					$tpl->assign('smarty_tpl', $startseite['smarty']);
					// languages
					$availableLanguages = GetAvailableLanguages();
					$tpl->assign('languageList', 	$availableLanguages);
					$tpl->assign('mobileURL', 		$bm_prefs['mobile_url']);
					// template uebergeben
					$tpl->assign('page', $this->_templatePath('eigenestartseiten.filehandler.tpl'));
					$tpl->display('nli/index.tpl');
					exit();
				}
			}
		}
		if($file=='start.php' && $action =="start")
		{
			$res = $db->Query('SELECT eigenestartseiten_li FROM {pre}prefs LIMIT 1');
			$prefs = $res->FetchArray();
			$res->Free();

			if($prefs['eigenestartseiten_li'] == 1)
			{
				$tpl->assign('activeTab', 'plugin_startpage_li');
				$tpl->assign('pageTitle', $lang_user['start']);

				/**
				* page sidebar
				*/
				$tpl->assign('pageMenuFile', 'li/start.sidebar.tpl');

				/**
				* dashboard
				*/
				$dashboard = _new('BMDashboard', array(BMWIDGET_START));

				$widgetOrder = $thisUser->GetPref('widgetOrderStart');
				if($widgetOrder === false || trim($widgetOrder) == '')
					$widgetOrder = $bm_prefs['widget_order_start'];

				$tpl->assign('pageTitle', $lang_user['welcome']);
				$tpl->assign('widgetOrder', $widgetOrder);
				$tpl->assign('widgets', $dashboard->getWidgetArray($widgetOrder));
				$tpl->assign('pageContent', 'li/start.page.tpl');
				$tpl->display('li/index.tpl');
				exit();
			}
		}
		if($file=='start.php' && $action=='')
		{
			$res = $db->Query('SELECT eigenestartseiten_li FROM {pre}prefs LIMIT 1');
			$prefs = $res->FetchArray();
			$res->Free();

			if($prefs['eigenestartseiten_li'] == 1)
			{
				// pruefen ob es eine Startseite gibt
				$res = $db->Query('SELECT * FROM {pre}plugin_eigenestartseiten WHERE lang=? AND typ=? LIMIT 1', 
					$currentLanguage,
					(int) 1);

				if($res->RowCount() == 0)
				{
					$res->Free();

					$res = $db->Query('SELECT * FROM {pre}plugin_eigenestartseiten WHERE lang=? AND typ=? LIMIT 1', 
						':all:',
						(int) 1);

					if($res->RowCount() == 0)
					{
						header("Location: start.php?action=start&sid=".session_id());
						exit();
					}
				}

				$startseite = $res->FetchArray();
				$res->Free();

				// title und seite uebergeben
				$tpl->assign('pageTitle', $startseite['title']);
				$tpl->assign('text', $startseite['seite']);
				$tpl->assign('smarty_tpl', $startseite['smarty']);
				// aktives Tab und sidebar uebergeben
				$tpl->assign('activeTab', 'start');
				$tpl->assign('pageMenuFile', 'li/start.sidebar.tpl');
				//$tpl->assign('pageToolbarFile', '');			

				// template uebergeben
				$tpl->assign('pageContent', $this->_templatePath('eigenestartseiten.filehandler.tpl'));
				$tpl->display('li/index.tpl');
				exit();
			}
		}
	}
}
/*
 * register plugin
 */
$plugins->registerPlugin('eigenestartseiten');
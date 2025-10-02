<?php
declare(strict_types=1);

/**
 * Custom Pages Plugin
 * 
 * Allows creation and management of custom pages within b1gMail.
 * Provides functionality to create, edit, and publish custom content pages.
 * 
 * @version 1.5.0
 * @since PHP 8.3
 * @license GPL
 */
class eigeneseiten extends BMPlugin 
{
	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 = 'page1';
	private const ADMIN_PAGE2 = 'page2';
	private const ADMIN_PAGE3 = 'page3';
	private const ADMIN_CACHE = 'cache';

	/**
	 * Shortlinks configuration
	 */
	private const SHORTLINKS_ALLOW = true;
	private const SHORTLINKS_USE = false;

	/**
	 * PHP 8.3: Readonly properties for immutable values
	 */
	private readonly string $pluginName;
	private readonly string $pluginVersion;
	private readonly string $pluginAuthor;

	/**
	 * Shortlinks configuration properties
	 */
	public bool $_shortlinks_allow = self::SHORTLINKS_ALLOW;
	public bool $_shortlinks_use = self::SHORTLINKS_USE;

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
		$this->pluginName 			= 'Eigene Seiten';
		$this->pluginVersion 		= '1.5.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name 				= $this->pluginName;
		$this->version 				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type 				= BMPLUGIN_DEFAULT;

		$this->author 				= $this->pluginAuthor;

		$this->admin_pages 			= true;
		$this->admin_page_title 	= $this->pluginName;
		$this->admin_page_icon 		= "eigeneseiten_icon.png";
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
	 * @global array $lang_user Language variables for user area
	 */
	public function AdminHandler(): void
	{
		global $tpl, $lang_admin, $lang_user;

		// Plugin call without action
		$action = $_REQUEST['action'] ?? self::ADMIN_PAGE1;

		// Tabs in admin area
		$tabs = [
			0 => [
				'title' => $lang_user['pages'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active' => $action === self::ADMIN_PAGE1,
				'icon' => '../plugins/templates/images/eigeneseiten_logo.png'
			],
			1 => [
				'title' => $lang_admin['create'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active' => $action === self::ADMIN_PAGE2,
				'icon' => './templates/images/extension_add.png'
			],
			2 => [
				'title' => $lang_admin['faq'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE3 . '&',
				'active' => $action === self::ADMIN_PAGE3,
				'icon' => './templates/images/faq32.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($_REQUEST['action'] === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('eigeneseiten1.pref.tpl'));
			$this->_Page1();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('eigeneseiten2.pref.tpl'));
			$this->_Page2();
		} elseif($_REQUEST['action'] === self::ADMIN_PAGE3) {
			$tpl->assign('page', $this->_templatePath('eigeneseiten3.pref.tpl'));
		} elseif($_REQUEST['action'] === self::ADMIN_CACHE) {
			$this->_Page4();
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

		$lang_admin['eigeneseiten_name']				= 'Eigene Seiten';
		$lang_admin['eigeneseiten_text']				= 'Erstellen Sie eigene Seiten und lassen Sie diese in b1gMail anzeigen.';

		if (strpos($lang, 'deutsch') !== false) {
			$lang_admin['eigeneseiten_own']					= 'Eigene';
			$lang_admin['eigeneseiten_linktitle']			= 'Link Title';
			$lang_admin['eigeneseiten_published']			= 'ver&ouml;ffentlicht';
			$lang_admin['eigeneseiten_published_link']		= 'Link im Men&uuml; anzeigen';
			$lang_admin['eigeneseiten_published_quicklinks']= 'Link im Widget "Eigene Seiten Quick-Links" anzeigen';
			$lang_admin['eigeneseiten_published_link_s1']	= 'Men&uuml;';
			$lang_admin['eigeneseiten_publish']				= 'ver&ouml;ffentlichen';
			$lang_admin['eigeneseiten_add']					= 'Hinzuf&uuml;gen';
			$lang_admin['eigeneseiten_tab_order']			= 'Tab Reihenfolge';
			$lang_admin['eigeneseiten_smarty']				= 'mit Smarty parsen';
		} else {
			$lang_admin['eigeneseiten_own']					= 'own';
			$lang_admin['eigeneseiten_linktitle']			= 'link title';
			$lang_admin['eigeneseiten_published']			= 'released';
			$lang_admin['eigeneseiten_published_link']		= 'show link at the menu';
			$lang_admin['eigeneseiten_published_quicklinks']= 'show link at the Widget "Eigene Seiten Quick-Links" anzeigen';
			$lang_admin['eigeneseiten_published_link_s1']	= 'menu;';
			$lang_admin['eigeneseiten_publish']				= 'announce';
			$lang_admin['eigeneseiten_add']					= 'add';
			$lang_admin['eigeneseiten_tab_order']			= 'tab order';
			$lang_admin['eigeneseiten_smarty']				= 'parse with smarty';
		}

		$lang_admin['icon_modern']						= 'Icon f&uuml;r modernes Template';
		$lang_admin['icon_modern_active']				= 'Aktives Icon f&uuml;r modernes Template';
		$lang_admin['eigeneseiten_sidebar']				= 'Sidebar';
		$lang_admin['eigeneseiten_toolbar']				= 'Toolbar';
		$lang_admin['quicklinks']						= $lang_user['quicklinks'];
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
		global $db, $bm_prefs;

		$db->Query('CREATE TABLE IF NOT EXISTS `{pre}plugin_eigeneseiten` (
			`id` int(11) NOT NULL auto_increment,
			`icon` varchar(255) NOT NULL,
			`title` varchar(255) NOT NULL,
			`link_title` varchar(255) NOT NULL,
			`typ` int(1) NOT NULL,
			`published` int(1) NOT NULL,
			`published_link` int(1) NOT NULL,
			`published_quicklinks` int(1) NOT NULL,
			`views` int(11) NOT NULL,
			`li_sidebar` varchar(255) NOT NULL,
			`li_toolbar` varchar(255) NOT NULL,
			`gruppe` int(11) NOT NULL,
			`lang` varchar(255) NOT NULL,
			`tab_order` int(11) NOT NULL,
			`seite` text NOT NULL,
			`smarty` int(1) NOT NULL,
			PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 ;');

		$db->Query('ALTER TABLE `{pre}plugin_eigeneseiten` ADD `icon_modern` varchar(255) NOT NULL');
		$db->Query('ALTER TABLE `{pre}plugin_eigeneseiten` ADD `icon_modern_active` varchar(255) NOT NULL');

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
		global $db;

		$db->Query('DROP TABLE {pre}plugin_eigeneseiten');

		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Admin page 1: Pages overview
	 * 
	 * Displays all custom pages with management options.
	 * Allows deletion, publishing, and editing of pages.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		// delete 
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete')
		{
			//loeschen der seite
			$db->Query('Delete FROM {pre}plugin_eigeneseiten Where id=?', 
				(int) $_REQUEST['id']);
		}
		// publish 
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'publish')
		{
			//veroeffentlichen des seite
			$db->Query('UPDATE {pre}plugin_eigeneseiten SET published=? WHERE id=?',
				(int) 1,
				(int) $_REQUEST['id']);
		}

		// eigeneseiten abfragen und auf Page1 ausgeben
		$eigeneseiten = array();
		$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten ORDER by id ASC');
		while($row = $res->FetchArray())
		{
			$eigeneseiten[$row['id']] = array(
				'id'					=> $row['id'],
				'title'					=> $row['title'],
				'link_title'			=> $row['link_title'],
				'typ'					=> $row['typ'],
				'published'				=> $row['published'],
				'published_link'		=> $row['published_link'],
				'published_quicklinks'	=> $row['published_quicklinks'],
				'views'					=> $row['views'],
			);
		}
		$res->Free();

		$tpl->assign('eigeneseiten', $eigeneseiten);
	}

	/*
	*  Eintragen neuer oder bearbeiten alter Seiten
	*/
	/**
	 * Admin page 2: Create new page
	 * 
	 * Handles creation and editing of custom pages.
	 * Processes form submissions and validates input.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 */
	private function _Page2(): void
	{
		global $tpl, $db, $bm_prefs;

		// neue Seite hinzufuegen
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			// DB INSEERT
			$res = $db->Query('INSERT INTO {pre}plugin_eigeneseiten(icon, title, link_title, typ, published, published_link, published_quicklinks, views, li_sidebar, li_toolbar, gruppe, lang, seite, tab_order, smarty,icon_modern,icon_modern_active) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', 
				$_REQUEST['icon'],
				$_REQUEST['title'],
				$_REQUEST['link_title'],
				(int) $_REQUEST['typ'],
				(int) isset($_REQUEST['published']) ? 1 : 0,
				(int) isset($_REQUEST['published_link']) ? 1 : 0,
				(int) isset($_REQUEST['published_quicklinks']) ? 1 : 0,
				(int) 0,
				$_REQUEST['li_sidebar'],
				$_REQUEST['li_toolbar'],
				(int) $_REQUEST['gruppe'],
				$_REQUEST['lang'],
				$_REQUEST['text'],
				(int) $_REQUEST['order'],
				(int) isset($_REQUEST['smartyparsen']) ? 1 : 0,
				$_REQUEST['icon_modern'],
				$_REQUEST['icon_modern_active']);
		}

		// Seite updaten
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'update')
		{
			// DB UPDATE
			$db->Query('UPDATE {pre}plugin_eigeneseiten SET icon=?,title=?,link_title=?,typ=?,published=?,published_link=?,published_quicklinks=?,li_sidebar=?,li_toolbar=?,gruppe=?,lang=?,seite=?,tab_order=?,smarty=?,icon_modern=?,icon_modern_active=? WHERE id=?',
				$_REQUEST['icon'],
				$_REQUEST['title'],
				$_REQUEST['link_title'],
				(int) $_REQUEST['typ'],
				(int) isset($_REQUEST['published']) ? 1 : 0,
				(int) isset($_REQUEST['published_link']) ? 1 : 0,
				(int) isset($_REQUEST['published_quicklinks']) ? 1 : 0,
				$_REQUEST['li_sidebar'],
				$_REQUEST['li_toolbar'],
				(int) $_REQUEST['gruppe'],
				$_REQUEST['lang'],
				$_REQUEST['text'],
				(int) $_REQUEST['order'],
				(int) isset($_REQUEST['smartyparsen']) ? 1 : 0,
				$_REQUEST['icon_modern'],
				$_REQUEST['icon_modern_active'],
				(int) $_REQUEST['id']);
		}

		//Seiten daten fuer update abrufen
		if(isset($_REQUEST['id']))
		{
			// Seiten daten
			$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten WHERE id=?', 
				(int) $_REQUEST['id']);
			$eigeneseiten = $res->FetchArray();
			$res->Free();

			$tpl->assign('eigeneseite', $eigeneseiten);
			$tpl->assign('id', true);
		} else {
			$tpl->assign('id', false);
		}

		// Pfade auf icon, sidebar und toolbar
		$d1 = dir(B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/images/li/');
		$d2 = dir(B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/li/');
		$d3 = dir(B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/li/');

		// array fuer icon, sidebar, toolbar und gruppen
		$array_icon = $array_sidebar = $array_toolbar = $gruppen = array();
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
		// array sidebar fuellen
		$i = 0;
		while (false !== ($entry = $d2->read())) {
			if (preg_match('/.sidebar.tpl/',$entry))
			{
				$entry_name = $entry;
				$entry_name = str_replace(".sidebar.tpl", "", $entry_name);
	
				$array_sidebar[$i] = array(
					'small_name'		=> $entry_name,
					'full_name'			=> $entry,
				);
				$i++;
			}  
		}
		$d2->close();
		// array toolbar fuellen
		$i = 0;
		while (false !== ($entry = $d3->read())) {
			if (preg_match('/.toolbar.tpl/',$entry) AND !preg_match('/organizer.calendar/',$entry))
			{
				$entry_name = $entry;
				$entry_name = str_replace(".toolbar.tpl", "", $entry_name);

				$array_toolbar[$i] = array(
					'small_name'		=> $entry_name,
					'full_name'			=> $entry,
				);
				$i++;
			}  
		}
		$d3->close();
		// array gruppen fuellen
		$res = $db->Query('SELECT id, titel FROM {pre}gruppen ORDER by titel ASC');
		while($row = $res->FetchArray())
		{
			$gruppen[$row['id']] = array(
				'id'				=> $row['id'],
				'titel'				=> $row['titel'],
			);
		}
		$res->Free();

		// alle sprachen
		$tpl->assign('languages', GetAvailableLanguages());
		// variablen fuer icon, sidebar und toolbar
		$tpl->assign('array_icon', $array_icon);
		$tpl->assign('array_sidebar', $array_sidebar);
		$tpl->assign('array_toolbar', $array_toolbar);
		// alle gruppen
		$tpl->assign('gruppen', $gruppen);
		// variable fuer den editor
		$tpl->assign('usertpldir', B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/');
	}


	/**
	 * Admin page 4: Create or update cache
	 * 
	 * Create or update template cache for custom pages.
	 * 
	 * @return void
	 */
	private function _Page4(): void
	{
		global $db, $cacheManager;

		$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten ORDER by id ASC');
		while($row = $res->FetchArray())
		{
			$eigeneseite = $cacheManager->Get('eigeneseiten_'.$row['id']);
			if($eigeneseite === false) {
				$cacheManager->Add('eigeneseiten_'.$row['id'], $row, (7*24*60*60));
			} else {
				$cacheManager->Set('eigeneseiten_'.$row['id'], $row, (7*24*60*60));
			}
		}
		$res->Free();

		header('Location: plugin.page.php?plugin=eigeneseiten&action=page1&sid=' . session_id());
		exit();
	}

	/*
	*  Links auf der Seite anzeigen
	*/
	function getUserPages($loggedin)
	{
		global $db, $userRow, $currentLanguage;

		// links array
		$links = array();

		// prueft ob (li und both) oder (nli und both)
		if($loggedin)
		{
			/*li und both
			typ  = 1 (loggedin) oder 2  (alle)
			gruppe = $userRow['gruppe'] oder 0 (alle)
			lang =  $currentLanguage oder ':all:' (alle)
			*/
			$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten  WHERE published=? AND published_link=? AND (typ=? OR typ=?) AND (gruppe=? OR gruppe=?) AND (lang=? OR lang=?) ORDER by id ASC',
				(int) 1,
				(int) 1,
				(int) 1,
				(int) 2,
				(int) $userRow['gruppe'],
				(int) 0,
				$currentLanguage,
				':all:');

			// array fuellen
			while($row = $res->FetchArray())
			{
				$arrayid = 'plugin_page'.$row['id'];
				if($this->_shortlinks_use)
				{
					$link = 'start.php?page_id='.$row['id'].'&sid=';
				} else {
					$link = 'start.php?action=plugin_page&id='.$row['id'].'&sid=';
				}

				$links[$arrayid] = array(
					'icon'					=> $row['icon'],
					'icon_modern'			=> $row['icon_modern'],
					'icon_modern_active'	=> $row['icon_modern_active'],
					'link'        			=> $link,
					'text'					=> $row['link_title'],
					'order'					=> $row['tab_order']
				);
			}
			$res->Free();
		} else {
			/*nli und both
			typ  = 0(notloggedin) oder 2  (alle)
			lang =  $currentLanguage oder ':all:' (alle)
			*/
			$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten  WHERE published=? AND published_link=? AND (typ=? OR typ=?) AND (lang=? OR lang=?) ORDER by id ASC',
				(int) 1,
				(int) 1,
				(int) 0,
				(int) 2,
				$currentLanguage,
				':all:');

			// array fuellen
			while($row = $res->FetchArray())
			{
				$arrayid = 'plugin_page'.$row['id'];
				if($this->_shortlinks_use)
				{
					$link = 'index.php?page_id='.$row['id'];
				} else {
					$link = 'index.php?action=plugin_page&id='.$row['id'];
				}
			
				$links[$arrayid] = array(
					'link'        => $link,
					'text'        => $row['link_title']
				);
			}
			$res->Free();
		}
		return $links;
	}

	/*
	*  Anzeigen der Seiten
	*/
	function FileHandler($file, $action) {
		global $tpl, $db, $bm_prefs, $groupRow, $userRow, $currentLanguage, $thisUser, $cacheManager;

		// nicht eingeloggte Seiten
		if($file=='index.php' && (($action=='plugin_page') OR ($this->_shortlinks_allow && isset($_REQUEST['page_id']))))
		{
			if($this->_shortlinks_allow && isset($_REQUEST['page_id']))
			{
				$_REQUEST['id'] = $_REQUEST['page_id'];
			}

			$eigeneseite = $cacheManager->Get('eigeneseiten_'.$_REQUEST['id'], (7*24*60*60));
			if($eigeneseite === false){
				// pruefen ob es eine Seite mit id gibt
				$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten WHERE id=? AND published=? AND (lang=? OR lang=?)', 
					(int) $_REQUEST['id'],
					(int) 1,
					$currentLanguage,
					':all:');
				if($res->RowCount() == 1)
				{
					$eigeneseite = $res->FetchArray();
					$res->Free();
					$cacheManager->Add('eigeneseiten_'.$_REQUEST['id'], $eigeneseite);
				} else {
					// id gibt es nicht , nicht published, oder nicht selbe sprache
					DisplayError(__LINE__, "Unauthorized", "You are not authrized to view or change this dataset or page. Possible reasons are too few permissions or an expired session.", "Diese Seite existiert nicht oder nicht mehr. Bitte &uuml;berpr&uuml;fen Sie die Adresse.", __FILE__, __LINE__);
					exit();
				}
			}
			
			if($_REQUEST['id']==4 OR $_REQUEST['id']==5 OR $_REQUEST['id']==6 OR $_REQUEST['id']==7 OR $_REQUEST['id']==19)
				$tpl->addJSFile('nli',			"./clientlib/overlay.js");
			
			// title, seite und smarty uebergeben
			$tpl->assign('pageTitle', $eigeneseite['title']);
			$tpl->assign('text', $eigeneseite['seite']);
			$tpl->assign('smarty_tpl', $eigeneseite['smarty']);
			// languages
			$availableLanguages = GetAvailableLanguages();
			$tpl->assign('languageList', $availableLanguages);
			// template uebergeben
			$tpl->assign('page', $this->_templatePath('eigeneseiten.filehandler.tpl'));
			$tpl->display('nli/index.tpl');

			// views hochzaehlen
			$db->Query('UPDATE {pre}plugin_eigeneseiten SET views=? WHERE id=?',
				(int) $eigeneseite['views']+1,
				(int) $_REQUEST['id']);
			exit();
		}

		//eingeloggte Seiten		
		if($file=='start.php' && (($action=='plugin_page') OR ($this->_shortlinks_allow && isset($_REQUEST['page_id']))))
		{
			if($this->_shortlinks_allow && isset($_REQUEST['page_id']))
			{
				$_REQUEST['id'] = $_REQUEST['page_id'];
			}
		
			// pruefen ob es eine Seite mit id gibt
			$res = $db->Query('SELECT * FROM {pre}plugin_eigeneseiten WHERE id=? AND published=? AND (gruppe=? OR gruppe=?) AND (lang=? OR lang=?)', 
				(int) $_REQUEST['id'],
				(int) 1,
				(int) 0,
				(int) $userRow['gruppe'],
				$currentLanguage,
				':all:');			
			if($res->RowCount() == 1)
			{
				$eigeneseite = $res->FetchArray();
				$res->Free();

				//akitves Tab
				$activeTab = 'plugin_page'.$eigeneseite['id'];
				//sidebar
				$pageMenuFile = 'li/'.$eigeneseite['li_sidebar'].'.sidebar.tpl';

				// include fuer prefs.sidebar
				if($eigeneseite['li_sidebar'] == "prefs")
				{
					$prefsItems = $prefsImages = $prefsIcons = array();
					$prefsItems['common'] = true;
					$prefsItems['contact'] = true;
					if($groupRow['smime'] == 'yes')
						$prefsItems['keyring'] = true;
					$prefsItems['signatures'] = true;
					$prefsItems['filters'] = true;
					if($bm_prefs['use_clamd'] == 'yes')
						$prefsItems['antivirus'] = true;
					if($bm_prefs['use_bayes'] == 'yes' || $bm_prefs['spamcheck'] == 'yes')
						$prefsItems['antispam'] = true;
					if($groupRow['aliase'] > 0)
						$prefsItems['aliases'] = true;
					if($groupRow['responder'] == 'yes')
						$prefsItems['autoresponder'] = true;
					if($groupRow['ownpop3'] > 0)
						$prefsItems['extpop3'] = true;
					if($bm_prefs['gut_regged'] == 'yes')
						$prefsItems['coupons'] = true;
					if($groupRow['checker'] == 'yes')
						$prefsItems['software'] = true;
					$prefsItems['faq'] = true;
					$prefsItems['membership'] = true;
					$tpl->assign('prefsItems', $prefsItems);
					$tpl->assign('prefsImages', $prefsImages);
					$tpl->assign('prefsIcons', $prefsIcons);
				}
				// include fuer organizer.sidebar
				if($eigeneseite['li_sidebar'] == "organizer")
				{
					include('./serverlib/todo.class.php');
					$todo = _new('BMTodo', array($userRow['id']));
					$tpl->assign('tasks', $todo->GetTodoList('faellig', 'asc', 5));
				}
				// include fuer email.sidebar oder  email.toolbar
				if($eigeneseite['li_sidebar'] == "email" OR $eigeneseite['li_toolbar'] == "email")
				{
					include('./serverlib/mailbox.class.php');
					include('./serverlib/email.top.php');
				}
				// variablen fuer webdisk.toolbar
				if($eigeneseite['li_toolbar'] == "webdisk")
				{
					include('./serverlib/webdisk.class.php');

					$webdisk = _new('BMWebdisk', array($userRow['id']));
					$folderID = !isset($_REQUEST['folder']) ? 0 : (int)$_REQUEST['folder'];
					$folderPath = $webdisk->GetFolderPath($folderID);
					$spaceLimit = $webdisk->GetSpaceLimit();
					$usedSpace = $webdisk->GetUsedSpace();

					$tpl->assign('spaceUsed', $usedSpace);
					$tpl->assign('trafficUsed', $userRow['traffic_down'] + $userRow['traffic_up']);
					$tpl->assign('spaceLimit', $spaceLimit);
					$tpl->assign('trafficLimit', $groupRow['traffic']);
					$tpl->assign('viewMode', ($viewMode = $thisUser->GetPref('webdiskViewMode')) === false ? 'icons' : $viewMode);
					$tpl->assign('folderID', $folderID);
				}
				// variablen fuer sms.toolbar
				if($eigeneseite['li_toolbar'] == "sms")
				{
					include('./serverlib/sms.class.php');
					$tpl->assign('accBalance', $thisUser->GetBalance());
				}

				// variblen zusammen setzen
				if($eigeneseite['li_toolbar'] == "0")
				{
					$tpl->assign('pageToolbarFile', '');
				} else {
					$tpl->assign('pageToolbarFile', 'li/'.$eigeneseite['li_toolbar'].'.toolbar.tpl');
				}

				// Variablen in Seite �ndern
				$page_text = $eigeneseite['seite'];
				$page_text = str_replace("[vorname]", $userRow['vorname'], $page_text);
				$page_text = str_replace("[nachname]", $userRow['nachname'], $page_text);
				$page_text = str_replace("[email]", $userRow['email'], $page_text);
				$page_text = str_replace("[gruppenname]", $groupRow['titel'], $page_text);

				// title, seite und smarty uebergeben
				$tpl->assign('pageTitle', $eigeneseite['title']);
				$tpl->assign('text', $page_text);
				$tpl->assign('smarty_tpl', $eigeneseite['smarty']);
				// aktives Tab und sidebar uebergeben
				$tpl->assign('activeTab', $activeTab);
				$tpl->assign('pageMenuFile', $pageMenuFile);	
				// template uebergeben
				$tpl->assign('pageContent', $this->_templatePath('eigeneseiten.filehandler.tpl'));
				$tpl->display('li/index.tpl');

				// views hochzaehlen
				$db->Query('UPDATE {pre}plugin_eigeneseiten SET views=? WHERE id=?',
					(int) $eigeneseite['views']+1,
					(int) $_REQUEST['id']);
				exit();
			} else {
				// id gibt es nicht oder nicht published
				DisplayError(__LINE__, "Unauthorized", "You are not authrized to view or change this dataset or page. Possible reasons are too few permissions or an expired session.", "Diese Seite existiert nicht oder nicht mehr. Bitte &uuml;berpr&uuml;fen Sie die Adresse.", __FILE__, __LINE__);
				exit();
			}
		}
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
$plugins->registerPlugin('eigeneseiten');
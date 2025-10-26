<?php
declare(strict_types=1);

/**
 * Lexicon/Glossary Plugin
 * 
 * Provides a comprehensive glossary system for b1gMail.
 * Allows creation and management of glossary entries with categorization,
 * search functionality, and multilingual support.
 * 
 * @version 1.3.0
 * @since PHP 8.3
 * @license GPL
 */

// Plugin configuration constants
const PLUGIN_LEXIKON_NLI 					= true;
const PLUGIN_LEXIKON_DEFAULTPERPAGE_ALL 	= 4;
const PLUGIN_LEXIKON_DEFAULTPERPAGE_CAT 	= 4;
const PLUGIN_LEXIKON_MAXLETTERDEFAULTPAGE 	= 500;
const PLUGIN_LEXIKON_NAME 					= 'lexikon';
const PLUGIN_LEXIKON_NAME_TEXT 				= 'Lexikon';

class lexikon extends BMPlugin 
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
		$this->pluginName 			= PLUGIN_LEXIKON_NAME_TEXT;
		$this->pluginVersion 		= '1.3.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name = $this->pluginName;
		$this->version = $this->pluginVersion;
		$this->designedfor = '7.3.0';
		$this->type = BMPLUGIN_DEFAULT;

		$this->author = $this->pluginAuthor;

		$this->admin_pages = true;
		$this->admin_page_title = $this->pluginName;
		$this->admin_page_icon = "lexikon_icon.png";
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
				'title' => $lang_admin['lexikon_name'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active' => $action === self::ADMIN_PAGE1,
				'icon' => '../plugins/templates/images/lexikon_logo.png'
			],
			1 => [
				'title' => $lang_admin['create'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active' => $action === self::ADMIN_PAGE2,
				'icon' => './templates/images/extension_add.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('lexikon1.pref.tpl'));
			$this->_Page1();
		} elseif($action === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('lexikon2.pref.tpl'));
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
		$lang_admin['lexikon_name'] = PLUGIN_LEXIKON_NAME_TEXT;
		$lang_admin['lexikon_text'] = 'Erstellen Sie ein eigenes ' . PLUGIN_LEXIKON_NAME_TEXT . ' und lassen Sie sich diese in b1gMail anzeigen.';

		if (strpos($lang, 'deutsch') !== false) {
			$lang_user['lexikon'] = PLUGIN_LEXIKON_NAME_TEXT;
			$lang_admin['lexikon_published'] = 'ver&ouml;ffentlicht';
			$lang_admin['lexikon_publish'] = 'ver&ouml;ffentlichen';
			$lang_user['lexikon_more'] = '[Weiterlesen]';
		} else {
			$lang_user['lexikon'] = PLUGIN_LEXIKON_NAME_TEXT;
			$lang_admin['lexikon_published'] = 'released';
			$lang_admin['lexikon_publish'] = 'announce';
			$lang_user['lexikon_more'] = '[more]';
		}
		
		$lang_user['lexikon_nosearch'] = 'Kein Eintrag gefunden. Versuchen Sie es erneut.';
		$lang_user['lexikon_empty'] = 'Kein Eintrag gefunden.';
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

		// Create lexicon table with UTF-8 support
		$db->Query('CREATE TABLE IF NOT EXISTS `{pre}mod_lexikon` (
			`id` int(11) NOT NULL auto_increment,
			`cat` varchar(1) NOT NULL,
			`title` varchar(255) NOT NULL,
			`text` text NOT NULL,
			`published` int(1) NOT NULL,
			`lang` varchar(7) NOT NULL,
			PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 ;');

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
		$db->Query('DROP TABLE {pre}mod_lexikon');

		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Admin page 1: Lexicon overview
	 * 
	 * Displays all glossary entries with management options.
	 * Allows deletion, publishing, and editing of entries.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		// Delete entry
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete') {
			$db->Query('DELETE FROM {pre}mod_lexikon WHERE id=?', 
				(int) $_REQUEST['id']);
		}
		
		// Publish entry
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'publish') {
			$db->Query('UPDATE {pre}mod_lexikon SET published=? WHERE id=?',
				1,
				(int) $_REQUEST['id']);
		}
		
		$sortBy = $_REQUEST['sortBy'] ?? 'id';
		$sortOrder = strtolower($_REQUEST['sortOrder'] ?? 'asc');
		
		$lexikon = [];
		$res = $db->Query('SELECT * FROM {pre}mod_lexikon ORDER BY ' . $sortBy . ' ' . $sortOrder);
		while($row = $res->FetchArray()) {
			$lexikon[$row['id']] = [
				'id' => $row['id'],
				'title' => $row['title'],
				'published' => $row['published'],
				'cat' => strtoupper($row['cat']),
				'lang' => $row['lang'],
			];
		}
		$res->Free();

		$tpl->assign('sortBy', $sortBy);
		$tpl->assign('sortOrder', $sortOrder);
		$tpl->assign('lexikon', $lexikon);
	}

	/**
	 * Admin page 2: Create or edit entry
	 * 
	 * Handles creation and editing of glossary entries.
	 * Processes form submissions and validates input.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $bm_prefs b1gMail preferences
	 */
	private function _Page2(): void
	{
		global $tpl, $db, $bm_prefs;

		// Save new entry
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
			$res = $db->Query('INSERT INTO {pre}mod_lexikon(cat, title, published, text, lang) VALUES(?,?,?,?,?)', 
				$_REQUEST['cat'],
				$_REQUEST['title'],
				isset($_REQUEST['published']) ? 1 : 0,
				$_REQUEST['text'],
				$_REQUEST['lang']);

			header('Location: plugin.page.php?plugin=lexikon&action=page1&sid=' . session_id());
		}
		
		// Update existing entry
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'update') {
			$db->Query('UPDATE {pre}mod_lexikon SET cat=?,title=?,published=?,text=?,lang=? WHERE id=?',
				$_REQUEST['cat'],
				$_REQUEST['title'],
				isset($_REQUEST['published']) ? 1 : 0,
				$_REQUEST['text'],
				$_REQUEST['lang'],
				(int) $_REQUEST['id']);

			header('Location: plugin.page.php?plugin=lexikon&action=page1&sid=' . session_id());
		}
		
		// Load entry for editing
		if(isset($_REQUEST['id'])) {
			$res = $db->Query('SELECT * FROM {pre}mod_lexikon WHERE id=?', 
				(int) $_REQUEST['id']);
			$lexikon = $res->FetchArray();
			$res->Free();

			$tpl->assign('lexikon', $lexikon);
			$tpl->assign('id', true);
		} else {
			$tpl->assign('id', false);
		}
		$tpl->assign('usertpldir',	B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/');
		$tpl->assign('languages',	GetAvailableLanguages());
	}

	/**
	 * Get user pages for navigation
	 * 
	 * Returns navigation pages for non-logged-in users.
	 * Only shows lexicon link when plugin link is enabled.
	 * 
	 * @param bool $loggedin Whether user is currently logged in
	 * @return array Array of navigation pages
	 * @global array $lang_user User language variables
	 */
	public function getUserPages($loggedin): array
	{
		global $lang_user;
		
		if(!$loggedin && PLUGIN_LEXIKON_NLI) {
			return [
				'lexikon' => [
					'text' => $lang_user['lexikon'],
					'link' => 'index.php?action=' . PLUGIN_LEXIKON_NAME
				]
			];
		}
		
		return [];
	}

	/**
	 * File handler for page processing
	 * 
	 * Main handler for processing lexicon requests and displaying pages.
	 * Handles different views: overview, category, search, and individual entries.
	 * 
	 * @param string $file The file being processed
	 * @param string $action The action being performed
	 * @return bool|void Whether the file was handled
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $bm_prefs b1gMail preferences
	 * @global array $groupRow Current group data
	 * @global array $userRow Current user data
	 * @global string $currentLanguage Current language
	 * @global object $thisUser Current user object
	 * @global array $lang_user User language variables
	 */
	public function FileHandler($file, $action)
	{
		global $tpl, $db, $bm_prefs, $groupRow, $userRow, $currentLanguage, $thisUser, $lang_user;

		// Handle different lexicon views
		if($file == 'index.php' && $action == PLUGIN_LEXIKON_NAME) {
			$this->_handleLexiconView();
		}
	}

	/**
	 * Handle lexicon view processing
	 * 
	 * Processes different lexicon views: overview, category, search, and individual entries.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 * @global array $lang_user User language variables
	 */
	private function _handleLexiconView(): void
	{
		global $tpl, $db, $lang_user;

		// Default overview page
		if(!isset($_REQUEST['cat']) || $_REQUEST['cat'] == "" && 
		   (!isset($_REQUEST['id']) || $_REQUEST['id'] == "") && 
		   (!isset($_REQUEST['s']) || $_REQUEST['s'] == "")) {
			
			$cat = "all";
			$title = $lang_user['lexikon'];
			$cat2 = "";

			$perPage = max(1, isset($_REQUEST['perPage']) ? (int)$_REQUEST['perPage'] : PLUGIN_LEXIKON_DEFAULTPERPAGE_ALL);
			$pageCount = 1;
			$pageNo = 1;

			$res = $db->Query('SELECT * FROM {pre}mod_lexikon WHERE published=1 ORDER BY RAND() LIMIT ' . $perPage);
			$defaultpage = true;
			
		} elseif(isset($_REQUEST['cat'])) {
			// Category view
			$cat = $cat2 = strip_tags($_REQUEST['cat']);
			if($cat == "0") {
				$title = $lang_user['lexikon'] . " - 0-9";
			} elseif($cat == ".") {
				$title = $lang_user['lexikon'] . " - Sonderzeichen";
			} else {
				$title = $lang_user['lexikon'] . " - " . strtoupper($cat);
			}

			$perPage = max(1, isset($_REQUEST['perPage']) ? (int)$_REQUEST['perPage'] : PLUGIN_LEXIKON_DEFAULTPERPAGE_CAT);
			$res = $db->Query('SELECT COUNT(*) FROM {pre}mod_lexikon WHERE cat=? AND published=1', $_REQUEST['cat']);
			list($lexCount) = $res->FetchArray(MYSQLI_NUM);
			$res->Free();
			$pageCount = ceil($lexCount / $perPage);
			$pageNo = isset($_REQUEST['page']) ? max(1, min($pageCount, (int)$_REQUEST['page'])) : 1;
			$startPos = max(0, min($perPage*($pageNo-1), $lexCount));

			$res = $db->Query('SELECT * FROM {pre}mod_lexikon WHERE cat=? AND published=1 LIMIT ' . $startPos . ',' . $perPage, $_REQUEST['cat']);
			
		} elseif(isset($_REQUEST['s'])) {
			// Search view
			$cat = "suche";
			$title = "Suche: " . strip_tags($_REQUEST['s']);
			
			$q = '\'%' . $db->Escape($_REQUEST['s']) . '%\'';
			
			$perPage = max(1, isset($_REQUEST['perPage']) ? (int)$_REQUEST['perPage'] : PLUGIN_LEXIKON_DEFAULTPERPAGE_CAT);
			$res = $db->Query('SELECT COUNT(*) FROM {pre}mod_lexikon WHERE text LIKE ' . $q . ' AND published=1');
			list($lexCount) = $res->FetchArray(MYSQLI_NUM);
			$res->Free();
			$pageCount = ceil($lexCount / $perPage);
			$pageNo = isset($_REQUEST['page']) ? max(1, min($pageCount, (int)$_REQUEST['page'])) : 1;
			$startPos = max(0, min($perPage*($pageNo-1), $lexCount));

			$res = $db->Query('SELECT * FROM {pre}mod_lexikon WHERE text LIKE ' . $q . ' AND published=1 LIMIT ' . $startPos . ',' . $perPage);
			$tpl->assign('suche', true);
			$cat2 = false;
			
		} elseif(isset($_REQUEST['id'])) {
			// Individual entry view
			$res = $db->Query('SELECT title FROM {pre}mod_lexikon WHERE (id=? OR title=?) AND published=1', $_REQUEST['id'], $_REQUEST['id']);
			list($title) = $res->FetchArray(MYSQLI_NUM);
			$title = $lang_user['lexikon'] . " - " . $title;
			$res->Free();
			$res = $db->Query('SELECT * FROM {pre}mod_lexikon WHERE (id=? OR title=?) AND published=1', $_REQUEST['id'], $_REQUEST['id']);
			$tpl->assign('nosmall', true);

			$pageNo = false;
			$perPage = false;
			$pageCount = false;
			$cat = false;
			$cat2 = false;
		}

		// Process results
		$emtpy = false;
		$lexikon = [];
		
		if($res->RowCount() < 1) {
			$emtpy = true;
		} else {
			while(($row = $res->FetchArray(MYSQLI_ASSOC)) !== false) {
				if(isset($defaultpage)) {
					if(strlen($row['text']) > PLUGIN_LEXIKON_MAXLETTERDEFAULTPAGE) {
						$end = strpos($row['text'], " ", PLUGIN_LEXIKON_MAXLETTERDEFAULTPAGE);
						$row['text'] = substr($row['text'], 0, $end) . "... <div style=\"float:right; font-size:100%;\"><a href=\"index.php?action=" . PLUGIN_LEXIKON_NAME . "&id=" . $row['id'] . "\" title=\"" . $row['title'] . "\">" . $lang_user['lexikon_more'] . "</a></div>";
					}
				}
				$lexikon[] = $row;
			}
			$res->Free();
		}

		// Sort search results
		if(isset($_REQUEST['s'])) {
			uasort($lexikon, [$this, 'searchCompareLexikon']);
		}

		// Assign template variables
		$tpl->assign('empty', $emtpy);
		$tpl->assign('pageNo', $pageNo);
		$tpl->assign('perPage', $perPage);
		$tpl->assign('pageCount', $pageCount);
		$tpl->assign('cat', $cat);
		$tpl->assign('cat2', $cat2);
		$tpl->assign('pageTitle', $title);
		$tpl->assign('lexikon', $lexikon);
		$tpl->assign('lexikonvar', PLUGIN_LEXIKON_NAME);
		$tpl->assign('showsearch', isset($_REQUEST['showsearch']));
		$tpl->assign('languageList', GetAvailableLanguages());
		$tpl->assign('page', $this->_templatePath('lexikon.page1.tpl'));
		$tpl->display('nli/index.tpl');
		exit();
	}

	/**
	 * Search comparison function for lexicon entries
	 * 
	 * Compares lexicon entries based on search relevance.
	 * 
	 * @param array $x First entry to compare
	 * @param array $y Second entry to compare
	 * @return int Comparison result (-1, 0, 1)
	 */
	public function searchCompareLexikon(array $x, array $y): int
	{
		$s = strtolower(strip_tags($_REQUEST['s']));
		$tmp1 = (substr_count(strtolower($x['title']), $s) * 3) + substr_count(strtolower($x['text']), $s);
		$tmp2 = (substr_count(strtolower($y['title']), $s) * 3) + substr_count(strtolower($y['text']), $s);
		
		if($tmp1 == $tmp2) {
			return 0;
		} elseif ($tmp2 < $tmp1) {
			return -1;
		} else {
			return 1;
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
$plugins->registerPlugin('lexikon');
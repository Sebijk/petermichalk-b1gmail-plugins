<?php
declare(strict_types=1);

/**
 * Show Shares Plugin
 * 
 * Provides an overview of users with webdisk shares and their contents.
 * Allows administrators to monitor shared folders and files across all users.
 * 
 * @version 1.2.0
 * @since PHP 8.2
 * @license GPL
 */
class showshares extends BMPlugin 
{
	/**
	 * Plugin constants
	 */
	private const PLUGIN_NAME 			= 'Show Shares';
	private const PLUGIN_VERSION 		= '1.2.0';
	private const PLUGIN_DESIGNEDFOR 	= '7.4.1';
	private const PLUGIN_AUTHOR 		= 'Peter Michalk';

	/**
	 * Action constants for admin pages
	 */
	private const ADMIN_PAGE1 			= 'page1';

	/**
	 * Plugin constructor
	 * 
	 * Initializes all plugin properties and configurations.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->name 				= self::PLUGIN_NAME;
		$this->version 				= self::PLUGIN_VERSION;
		$this->designedfor 			= self::PLUGIN_DESIGNEDFOR;
		$this->type 				= BMPLUGIN_DEFAULT;

		$this->author 				= self::PLUGIN_AUTHOR;

		$this->admin_pages 			= true;
		$this->admin_page_title 	= self::PLUGIN_NAME;
		$this->admin_page_icon 		= 'showshares_icon.png';
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
				'title' => $lang_admin['overview'],
				'link' => $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active' => $action === self::ADMIN_PAGE1,
				'icon' => '../plugins/templates/images/showshares_logo.png'
			]
		];

		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('showshares.pref.tpl'));
			$this->_Page1();
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
		$lang_admin['showshares_name']				= 'Show Shares';
		$lang_admin['showshares_text']				= 'Zeigt eine kleine Übersicht welcher Benutzer eine Webdisk Freigabe hat und welche Dateien dort enthalten sind.';
		
		$lang_admin['showshares_showall']			= 'Alle Ordner anzeigen.';
	}

	/**
	 * Plugin installation
	 * 
	 * Performs all necessary steps for plugin installation.
	 * Logs the installation process.
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
	 * Logs the uninstallation process.
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
	 * Admin page 1: Shares overview
	 * 
	 * Displays all webdisk shares with detailed information.
	 * Shows shared folders, files, and user statistics.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global object $db Database connection
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		// Save settings
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
			$this->_setPref("showall", $_REQUEST['showall']);
		}

		$showall = $this->_getPref("showall");

		// Load groups information
		$groups = [];
		$res = $db->Query('SELECT id, storage, webdisk, traffic, titel AS title FROM {pre}gruppen');
		while($row = $res->FetchArray(MYSQLI_ASSOC)) {
			$groups[$row['id']] = $row;
		}
		$res->Free();

		// Sort options
		$sortBy = $_REQUEST['sortBy'] ?? 'folder.id';
		$sortOrder = strtolower($_REQUEST['sortOrder'] ?? 'asc');

		$folders = $files = [];

		// Query folders based on showall setting
		if($showall) {
			$res	 = $db->Query('SELECT folder.*, user.email, user.diskspace_used, (user.traffic_down+user.traffic_up) AS traffic, user.traffic_status, user.gruppe FROM {pre}diskfolders AS folder INNER JOIN {pre}users AS user On user.id = folder.user ORDER BY ' . $sortBy . ' ' . $sortOrder);
		} else {
			$res	 = $db->Query('SELECT folder.*, user.email, user.diskspace_used, (user.traffic_down+user.traffic_up) AS traffic, user.traffic_status, user.gruppe FROM {pre}diskfolders AS folder INNER JOIN {pre}users AS user On user.id = folder.user WHERE folder.share = ? ORDER BY ' . $sortBy . ' ' . $sortOrder,
				"yes");
		}

		while($row = $res->FetchArray(MYSQLI_ASSOC)) {
			$row['diskspace_max'] = $groups[$row['gruppe']]['webdisk'];
			$row['traffic_max'] = $groups[$row['gruppe']]['traffic'];
			$row['count'] = 0;
			
			// Count files in folder
			$res2 = $db->Query('SELECT id, ordner, dateiname, size, contenttype FROM {pre}diskfiles WHERE ordner=?',
				$row['id']);
			while($row2 = $res2->FetchArray(MYSQLI_ASSOC)) {
				$files[] = $row2;
				$row['count']++;
			}
			$res2->Free();

			$folders[] = $row;
		}
		$res->Free();

		$tpl->assign('sortBy', 			$sortBy);
		$tpl->assign('sortOrder', 		$sortOrder);

		$tpl->assign('webdiskgalerie',	$this->CheckPluginInstalled('webdiskgalerie'));

		$tpl->assign('folders',			$folders);
		$tpl->assign('files',			$files);
		$tpl->assign('showall',			$showall);
	}

	/**
	 * Check if a plugin is installed
	 * 
	 * Queries the database to determine if a specific plugin is installed.
	 * 
	 * @param string $name Plugin name to check
	 * @return bool True if plugin is installed, false otherwise
	 * @global object $db Database connection
	 */
	private function CheckPluginInstalled(string $name): bool
	{
		global $db;

		$res = $db->Query('SELECT installed FROM {pre}mods WHERE modname = ?', $name);
		if($res->RowCount() == 1) {
			$row = $res->FetchArray(MYSQLI_ASSOC);
			$res->Free();
			return (bool)$row[0];
		} else {
			$res->Free();
			return false;
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
$plugins->registerPlugin('showshares');
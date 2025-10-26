<?php
declare(strict_types=1);

/**
 * Feed Parser Plugin
 * 
 * With this plugin you can create an extensive RSS directory with news feeds.
 * It allows fetching RSS feeds via cronjob or manual trigger and displays
 * them in a structured format.
 *  
 * @version 1.4.0
 * @since PHP 8.3
 * @license GPL
 */
class feedparser extends BMPlugin 
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
		$this->pluginName 			= 'RSS Feeds';
		$this->pluginVersion 		= '1.4.0';
		$this->pluginAuthor 		= 'dotaachen';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= $this->pluginAuthor;
		$this->mail					= 'b1g@dotaachen.net';
		$this->web 					= 'http://b1g.dotaachen.net';

		$this->update_url			= 'http://b1g.dotaachen.net/update/';
		$this->website				= 'http://b1g.dotaachen.net';

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "feedparser_logo.png";
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
				'title'		=> $lang_admin['feedparser_name'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/feedparser_logo.png'
			],
			1 => [
				'title'		=> $lang_admin['create'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active'	=> $action === self::ADMIN_PAGE2,
				'icon'		=> './templates/images/extension_add.png'
			],
			2 => [
				'title'		=> $lang_admin['prefs'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE3 . '&',
				'active'	=> $action === self::ADMIN_PAGE3,
				'icon'		=> './templates/images/ico_prefs_defaults.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('feedparser1.pref.tpl'));
			$this->_Page1();
		} elseif($action === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('feedparser2.pref.tpl'));
			$this->_Page2();
		} elseif($action === self::ADMIN_PAGE3) {
			$tpl->assign('page', $this->_templatePath('feedparser3.pref.tpl'));
			$this->_Page3();
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
	public function OnReadLang(&$lang_user,  &$lang_client, &$lang_custom, &$lang_admin, $lang): void
	{
		global $lang_user;

		$lang_admin['feedparser_name']					= "RSS Feeds";
		$lang_admin['feedparser_text']					= "Erstellen Sie ein umfangreiches RSS Verzeichnis mit News-Feeds.";
		$lang_admin['feedparser_rss']					= "RSS";

		if (strpos($lang, 'deutsch') !== false) {
			$lang_admin['feedparser_oncron']			= 'Abruf beim Cronjob zulassen?';
			$lang_admin['feedparser_onfilehandler']		= 'Abruf auf der Seite zulassen?';
			$lang_admin['feedparser_maxdate']			= 'Lebendauer einer Nachricht';
			
			$lang_admin['feedparser_bulk']				= 'Max. Nachrichten';
			$lang_admin['feedparser_defaultcategory']	= 'Standard Kategorie nutzen?';
			$lang_admin['feedparser_nopicture']			= 'Keine Bilder?';
		} else {
			$lang_admin['feedparser_oncron']			= 'Allow fetch on the Cronjob?';
			$lang_admin['feedparser_onfilehandler']		= 'Allow fetch on the Site?';
		}
		$lang_admin['interval']							= $lang_user['interval'] ?? '';
		$lang_admin['lastfetch']						= $lang_user['lastfetch'] ?? '';
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
		
		$db->Query('CREATE TABLE IF NOT EXISTS `{pre}mod_feednews_rss` (
			`id` int(11) NOT NULL auto_increment,
			`title` varchar(255) NOT NULL,
			`rss` varchar(255) NOT NULL,
			`interval` int(6) NOT NULL,
			`lastfetch` int(11) NOT NULL,
			`category` varchar(255) NOT NULL,
			`bulk` int(11) NOT NULL,
			`defaultcategory` int(1) NOT NULL,
			`nopicture` int(1) NOT NULL,
			`nolayer` int(1) NOT NULL,
			`regex` varchar(255) NOT NULL,
			PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 ;');

		$db->Query('CREATE TABLE IF NOT EXISTS `{pre}mod_feednews_news` (
			`id` int(11) NOT NULL auto_increment,
			`rss` int(11) NOT NULL,
			`title` varchar(255) NOT NULL,
			`text` text NOT NULL,
			`link` varchar(255) NOT NULL,
			`datum` int(11) NOT NULL,
			`category` varchar(255) NOT NULL,
			`picture` varchar(255) NOT NULL,
			PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 ;');
			
		//ALTER TABLE `db00041895`.`bm60_mod_feednews_news` ADD COLUMN `guid` VARCHAR(40) NOT NULL AFTER `id`, ADD INDEX (`guid`); 

		$this->_setPref("oncron",		1);
		$this->_setPref("filehandler",	1);
		$this->_setPref("maxdate",		90);

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
		
		$db->Query('DROP TABLE {pre}mod_feednews_rss');
		$db->Query('DROP TABLE {pre}mod_feednews_news');

		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Display RSS feeds list page
	 * 
	 * Shows all configured RSS feeds with their properties.
	 * Handles deletion of feeds and displays them in a table.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		// Delete RSS feed
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete')
		{
			$db->Query('Delete FROM {pre}mod_feednews_rss Where id=?',
				(int)$_REQUEST['id']);
		}

		$rss = [];
		$res = $db->Query('SELECT * FROM {pre}mod_feednews_rss ORDER by lastfetch ASC, id ASC');
		while($row = $res->FetchArray())
		{
			$rss[$row['id']] = [
				'id'				=> $row['id'],
				'title'				=> $row['title'],
				'rss'				=> $row['rss'],
				'interval'			=> $row['interval']/60,
				'lastfetch'			=> $row['lastfetch'],
				'category'			=> $row['category'],
				'nopicture'			=> $row['nopicture']
			];
		}
		$res->Free();
		$tpl->assign('rss', $rss);
	}

	/**
	 * RSS feed creation and editing page
	 * 
	 * Handles creation of new RSS feeds and editing of existing ones.
	 * Processes form data and saves/updates feed configurations.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 */
	private function _Page2(): void
	{
		global $tpl, $db;

		// Save new RSS feed
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			$res = $db->Query('INSERT INTO {pre}mod_feednews_rss(`title`, rss, `interval`, `lastfetch`, `category`, `bulk`, `defaultcategory`, `nopicture`, `regex`) VALUES(?,?,?,?,?,?,?,?,?)', 
				$_REQUEST['title'],
				$_REQUEST['rss'],
				(int)$_REQUEST['interval']*60,
				(int)0,
				$_REQUEST['category'],
				(int)$_REQUEST['bulk'],
				(int)isset($_REQUEST['defaultcategory']) ? 1 : 0,
				(int)isset($_REQUEST['nopicture']) ? 1 : 0,
				$_REQUEST['regex']);
			$res->Free();
		}
		
		// Update existing RSS feed
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'update')
		{
			$db->Query('UPDATE {pre}mod_feednews_rss SET `title`=?,rss=?,`interval`=?,`category`=?,`bulk`=?, `defaultcategory`=?, `nopicture`=?, `regex`=? WHERE id=?',
				$_REQUEST['title'],
				$_REQUEST['rss'],
				(int)$_REQUEST['interval']*60,
				$_REQUEST['category'],
				(int)$_REQUEST['bulk'],
				(int)isset($_REQUEST['defaultcategory']) ? 1 : 0,
				(int)isset($_REQUEST['nopicture']) ? 1 : 0,
				$_REQUEST['regex'],
				(int)$_REQUEST['id']);
		}
		
		// Load feed data for editing
		if(isset($_REQUEST['id']))
		{
			$res = $db->Query('SELECT * FROM {pre}mod_feednews_rss WHERE id=?', 
				(int)$_REQUEST['id']);
			$rss = $res->FetchArray();
			$res->Free();
			$rss['interval']	= $rss['interval']/60;

			$tpl->assign('rss', $rss);
			$tpl->assign('id', true);
		} else {
			$tpl->assign('id', false);
		}
	}

	/**
	 * Plugin settings page
	 * 
	 * Handles saving and displaying plugin configuration settings.
	 * Manages cronjob settings, file handler settings, and news retention.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 */
	private function _Page3(): void
	{
		global $tpl;

		// Save settings
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			$this->_setPref("oncron",		isset($_REQUEST['oncron']) ? 1 : 0);
			$this->_setPref("filehandler",	isset($_REQUEST['filehandler']) ? 1 : 0);
			$this->_setPref("maxdate",		$_REQUEST['maxdate']);
		}
		
		// Assign settings to template
		$tpl->assign('oncron',			$this->_getPref("oncron"));
		$tpl->assign('filehandler',		$this->_getPref("filehandler"));
		$tpl->assign('maxdate',			$this->_getPref("maxdate"));
	}

	/**
	 * Cron job handler
	 * 
	 * Executes RSS feed fetching when called by the cron job system.
	 * Only runs if cron fetching is enabled in plugin settings.
	 * 
	 * @return void
	 */
	public function OnCron(): void 
	{
		if($this->_getPref("oncron"))
		{
			$this->startFetch();
		}
	}

	/**
	 * File handler for manual feed fetching
	 * 
	 * Handles manual RSS feed fetching via web interface.
	 * Provides a secure token-based interface for triggering feed updates.
	 * 
	 * @param string $file The file being accessed
	 * @param string $action The action being performed
	 * @return void
	 */
	public function FileHandler(string $file, string $action): void
	{
		if($file == "index.php" && $action == "feedparser")
		{
			if($this->_getPref("filehandler")) 
			{
				if(($_REQUEST['token'] ?? '') == "" AND false === true )
				{
					header('Connection: close');
					header('Cache-Control: no-cache');
					header('Pragma: no-cache');
					header('Expires: Wed, 04 Aug 2004 14:46:00 GMT');
					@set_time_limit(0);
	
					$start = time();
					$this->startFetch();
					$end = time();
					echo "TIME - ".($end-$start);
					echo "<br/>OK - ";
					echo time();
					if(($end-$start) > 25) {
						PutLog('Feedparser took '.($end-$start).' seconds to execute.', PRIO_WARNING, __FILE__, __LINE__);
					}
				} else {
					DisplayError(__LINE__, "Disabled.", "This function has been disabled by the administrator.", "Configurable in admin area under \"Plugin -> Feed Parser -> Settings\".", __FILE__, __LINE__);
				}
			} else {
				DisplayError(__LINE__, "Disabled.", "This function has been disabled by the administrator.", "Configurable in admin area under \"Plugin -> Feed Parser -> Settings\".", __FILE__, __LINE__);
			}
			exit;
		}	
	}

	/**
	 * Start RSS feed fetching process
	 * 
	 * Main method that processes all configured RSS feeds.
	 * Checks which feeds need updating and fetches new content.
	 * 
	 * @return void
	 */
	private function startFetch(): void
	{
		global $db;
		
		$maxt = time()+10;
		$this->deleteNewsOnTime();

		$res = $db->Query('SELECT * FROM {pre}mod_feednews_rss ORDER by lastfetch ASC, id ASC');
		while($row = $res->FetchArray())
		{
			if($row['lastfetch']+$row['interval']-15 <= time()) {
				$rss = $this->getRSSFeed($row);
				$this->RSStoDB($rss, $row);
				$this->updateRSSFetchTime($row['id']);
				//$this->deleteNewsOnBulk($row['id'], $row['bulk']);
			}

			if($maxt < time()) {
				break;
			}
		}
		$this->updateCache();
		$res->Free();
	}

	/**
	 * Fetch RSS feed content
	 * 
	 * Downloads and parses RSS feed content from the given URL.
	 * 
	 * @param array $row RSS feed configuration row
	 * @return \SimpleXMLElement|false Parsed RSS feed or false on error
	 */
	private function getRSSFeed(array $row): \SimpleXMLElement|false
	{
		if(!class_exists('BMHTTP'))
			include(B1GMAIL_DIR . 'serverlib/http.class.php');

		$http		= _new('BMHTTP', array($row['rss']));
		$result		= $http->DownloadToString();
		$rss		= simplexml_load_string($result);
		return $rss;
	}

	/**
	 * Process RSS feed and store items in database
	 * 
	 * Parses RSS feed items and stores them in the database.
	 * Handles both RSS and Atom feed formats.
	 * 
	 * @param \SimpleXMLElement|false $rss Parsed RSS feed
	 * @param array $row RSS feed configuration
	 * @return void
	 */
	private function RSStoDB(\SimpleXMLElement|false $rss, array $row): void
	{
		if($rss === false) {
			return;
		}
		
		$item;		
		if(isset($rss->channel[0])) {
			if(isset($rss->channel[0]->item)) {
				$item			= $rss->channel[0]->item;
			}
		} else if(isset($rss->entry)) {
			$item				= $rss->entry;
		} else {
			return;
		}

		$time					= time();
		$z						= 0;
		while(true)
		{
			if(!isset($item[$z])) {
				break;
			}

			if(isset($item[$z]->pubDate)) {
				$date			= $item[$z]->pubDate;
			} else if(isset($item[$z]->published)) {
				$date			= $item[$z]->published;
			} else {
				$dc = $item[$z]->children('dc', true);
				if(count($dc)>0) {
  					$date		= $dc->date;
				} else {
					break;
				}
			}
			$date				= strtotime($date);

			if($date > $row['lastfetch'])
			{
				$guid			= $item[$z]->guid;
				if($guid=="") {
					$guid		= $item[$z]->id;
				}
				if($guid!="") {
					$guid		= md5($guid);
				}

				$title			= $item[$z]->title;
				$title			= $this->_convertText($title);
				$title			= $this->charText($title);
				//$title			= $this->replace_regex($title, $row);

				$link			= $this->_convertText($item[$z]->link);
				if($link=="") {
					$link		= $this->_convertText($item[$z]->url);
				}
				if($link=="") {
					$link		= $this->_convertText($item[$z]->link->attributes()->href);
				}
				$link			= $this->charText($link);

				if($this->isDuplicate($guid, $title, $link)) {
					$z++;
					continue;
				}
				
				$description	= $item[$z]->description;
				if($description=="") {
					$description= $item[$z]->summary;
				}

				$description	= $this->_convertText($description);
				$description	= $this->charText($description);
				$description	= $this->replace_regex($description, $row);

				if($row['defaultcategory']) {
					$category	= $row['category'];
				} else if(isset($item[$z]->category)) {
					$category	= $this->_convertText($item[$z]->category);
				} else {
					$category	= $row['category'];
				}

				$picture		= "";
				$enclosure		= $item[$z]->enclosure;
				if(count($enclosure)>0) {
					$picture	= $this->_convertText($enclosure->attributes()->url);
					$picture	= $this->charText($picture);
				}
				if($date > $time)
					$date 		= $time;

				$this->insertNews($row['id'], $guid, $title, $description, $link, $date, $category, $picture);
			} else {
				break;
			}
			$z++;
		}
	}

	/**
	 * Check if news item is duplicate
	 * 
	 * Checks if a news item already exists in the database
	 * to prevent duplicate entries.
	 * 
	 * @param string $guid Unique identifier for the item
	 * @param string $title News item title
	 * @param string $link News item link
	 * @return bool True if duplicate found, false otherwise
	 */
	private function isDuplicate(string $guid, string $title, string $link): bool
	{
		global $db;

		$res;
		if($guid!="")
		{
			$res = $db->Query('SELECT id FROM {pre}mod_feednews_news WHERE guid=? AND datum>=(unix_timestamp()-(24*60*60))',
				$guid);	
		} else {
			$res = $db->Query('SELECT id FROM {pre}mod_feednews_news WHERE (link=? OR title=?) AND datum>=(unix_timestamp()-(24*60*60))',
				$link,
				$title);
		}
		if($res->RowCount() >= 1)
		{
			$res->Free();
			return true;
		} else {
			$res->Free();
			return false;
		}
	}

	/**
	 * Insert news item into database
	 * 
	 * Stores a news item in the database with validation.
	 * 
	 * @param int $id RSS feed ID
	 * @param string $guid Unique identifier
	 * @param string $title News title
	 * @param string $description News description
	 * @param string $link News link
	 * @param int $date Publication date
	 * @param string $category News category
	 * @param string $picture Picture URL
	 * @return bool True on success, false on error
	 */
	private function insertNews(int $id, string $guid, string $title, string $description, string $link, int $date, string $category, string $picture): bool
	{
		global $db;
		
		if($title=="" || $link=="" || $date=="" || $category=="")
		{
			$error = "feed corrupt";
			if($title=="") {
				$error = "field (title) empty";	
			} else if($link=="") {
				$error = "field (link) empty";	
			} else if($date=="") {
				$error = "field (date) empty";	
			} else if($category=="") {
				$error = "field (category) empty";	
			}
			PutLog('RSS feed ('.$id.') - '.$error, PRIO_WARNING, __FILE__, __LINE__);
			return false;	
		}

		$res = $db->Query('INSERT INTO {pre}mod_feednews_news(guid, rss, title, text, link, datum, category, picture) VALUES(?,?,?,?,?,?,?,?)', 
			$guid,
			$id,
			$title,
			$description,
			$link,
			$date,
			$category,
			$picture);
		$res->Free();

		return true;
	}

	/**
	 * Delete old news items based on retention time
	 * 
	 * Removes news items older than the configured retention period.
	 * 
	 * @return void
	 */
	private function deleteNewsOnTime(): void
	{
		global $db;
		
		$date		= $this->_getPref("maxdate");
		if($date == -1)
			return;

		$maxdate	= $date*60*60*24;
		$db->Query('Delete FROM {pre}mod_feednews_news Where datum<=?',
			time()-$maxdate);
	}


	/**
	 * Update RSS feed last fetch time
	 * 
	 * Updates the timestamp when a feed was last fetched.
	 * 
	 * @param int $id RSS feed ID
	 * @return void
	 */
	private function updateRSSFetchTime(int $id): void
	{
		global $db;
		
		$db->Query('UPDATE {pre}mod_feednews_rss SET lastfetch=? WHERE id=?',
			time(),
			$id);
	}
	
	/**
	 * Update news cache
	 * 
	 * Updates the cached news items for faster display.
	 * 
	 * @return void
	 * @global object $cacheManager Cache manager instance
	 */
	private function updateCache(): void
	{
		global $cacheManager, $db;
		
		$news		= [];
		$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss Order by datum DESC LIMIT 25');
		while($row = $res->FetchArray())
		{
			$offset 			= date("Z");
			$row['rssdatum']	= date("D, j M Y H:i:s ", ($row['datum']-$offset)).'GMT';
			$news[]	= $row;
		}
		$res->Free();
		$cache		= $cacheManager->Add('feednews_all', $news, 60*60);
		if($cache === false) {
			$cacheManager->Set('feednews_all', $news, 60*60);
		}  
	}

	/**
	 * Convert and clean text content
	 * 
	 * Strips HTML tags and converts text encoding.
	 * 
	 * @param string $text Input text
	 * @return string Cleaned text
	 */
	private function _convertText(string $text): string 
	{
		$text	= strip_tags($text);
		$text	= $this->_utf8($text);	
		return $text;
	}

	/**
	 * Convert text to UTF-8 encoding
	 * 
	 * Ensures text is properly encoded as UTF-8.
	 * 
	 * @param string $text Input text
	 * @return string UTF-8 encoded text
	 * @global string $currentCharset Current character set
	 */
	private function _utf8(string $text): string
	{
		global $currentCharset;

		if(mb_detect_encoding($text, "UTF-8") != "UTF-8") {
			$text	= utf8_decode($text);
		}

		if(strtolower($currentCharset) != 'utf-8') {
			return utf8_encode($text);
		}
		return $text;
	}

	/**
	 * Character text processing
	 * 
	 * Processes text for safe display (currently disabled).
	 * 
	 * @param string $t Input text
	 * @return string Processed text
	 */
	private function charText(string $t): string
	{
		//$t	= str_replace('&',	'&amp;',	$t);
		//$t	= str_replace('"',	'&quot;',	$t);
		//$t	= str_replace('<',	'&lt;',		$t);
		//$t	= str_replace('>',	'&gt;',		$t);
		return $t;
	}

	/**
	 * Apply regex replacement to text
	 * 
	 * Applies configured regex pattern to remove unwanted content.
	 * 
	 * @param string $str Input text
	 * @param array $row RSS feed configuration
	 * @return string Processed text
	 */
	private function replace_regex(string $str, array $row): string
	{
		if($row['regex'] != "") {
			$t = preg_replace($row['regex'], "", $str, -1);
			return $t;
		}
		return $str;
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
$plugins->registerPlugin('feedparser');
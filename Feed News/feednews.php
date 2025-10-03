<?php
declare(strict_types=1);

/**
 * Feed News Plugin
 * 
 * Displays RSS news in categories and provides a widget for news display.
 * This plugin works in conjunction with the Feed Parser plugin to show
 * parsed RSS feeds in a user-friendly format.
 *  
 * @version 1.2.0
 * @since PHP 8.3
 * @license GPL
 */
class feednews extends BMPlugin 
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
		$this->pluginName 			= 'RSS News';
		$this->pluginVersion 		= '1.2.0';
		$this->pluginAuthor 		= 'dotaachen';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_WIDGET;

		$this->author				= $this->pluginAuthor;
		$this->mail					= 'b1g@dotaachen.net';
		$this->web 					= 'http://b1g.dotaachen.net';

		$this->update_url			= 'http://b1g.dotaachen.net/update/';
		$this->website				= 'http://b1g.dotaachen.net';

		$this->admin_pages			= true;
		$this->admin_page_title		= $this->pluginName;
		$this->admin_page_icon		= "feednews_logo12.png";

		$this->widgetTitle			= 'Nachrichten';
		$this->widgetTemplate		= 'feednews.widget.tpl';
		$this->widgetIcon			= 'feednews_logo12.png';
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
				'title'		=> $lang_admin['overview'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/feednews_logo12.png'
			],
			1 => [
				'title'		=> $lang_admin['feednews_name'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE2 . '&',
				'active'	=> $action === self::ADMIN_PAGE2,
				'icon'		=> './templates/images/extension_add.png'
			],
			2 => [
				'title'		=> $lang_admin['create'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE3 . '&',
				'active'	=> $action === self::ADMIN_PAGE3,
				'icon'		=> './templates/images/extension_add.png'
			],
		];
		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('feednews1.pref.tpl'));
			$this->_Page1();
		} elseif($action === self::ADMIN_PAGE2) {
			$tpl->assign('page', $this->_templatePath('feednews2.pref.tpl'));
			$this->_Page2();
		} elseif($action === self::ADMIN_PAGE3) {
			$tpl->assign('page', $this->_templatePath('feednews3.pref.tpl'));
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
	public function OnReadLang(array &$lang_user, array &$lang_client, array &$lang_custom, array &$lang_admin, string $lang): void
	{
		global $lang_user;

		$lang_admin['feednews_name']				= "RSS News";
		$lang_admin['feednews_text']				= "RSS Nachrichten werden in Kategorien angezeigt.";
		$lang_admin['feednews_rss']					= "RSS";
		
		$lang_admin['feednews_unused']				= "ungenutzte";
		$lang_admin['feednews_link']				= "Link";
		$lang_admin['feednews_picture']				= "Bilder";

		$lang_user['feednews_redirect']				= "Sie verlassen jetzt wreckedmail�";
		$lang_user['feednews_nosearch']				= 'Kein Eintrag gefunden. Versuchen Sie es erneut.';
		$lang_user['feednews_empty']				= 'Kein Eintrag gefunden.';
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
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully installed.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
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
		PutLog('Plugin "'. $this->name .' - '. $this->version .'" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Overview page showing news categories
	 * 
	 * Displays statistics about news categories and unused categories.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 */
	private function _Page1(): void
	{
		global $tpl, $db;

		$notuse = $cat = [];
		$res = $db->Query('SELECT distinct category FROM {pre}mod_feednews_news Order by category');
		while($row = $res->FetchArray())
		{
			if($row['category'] == "")
				continue;

			$res2			= $db->Query('SELECT Count(*) FROM {pre}mod_feednews_news Where category=?',
				$row['category']);
			$count			= $res2->FetchArray();
			$res2->Free();
			$row['count']	= $count[0];
			$cat[]			= $row;
		}
		$res->Free();
		
		$notincat	= '"Schlagzeilen","Lifestyle","Politik","Wirtschaft","Technik","Unterhaltung","Sport","Panorama"';
		$res = $db->Query('SELECT distinct category FROM {pre}mod_feednews_news Where category NOT IN ('.$notincat.') Order by category');
		while($row = $res->FetchArray())
		{
			if($row['category'] == "")
				continue;

			$notuse[] = $row['category'];
		}
		
		$res		= $db->Query('SELECT Count(*) FROM {pre}mod_feednews_news');
		$count		= $res->FetchArray();
		$res->Free();

		$tpl->assign('cat',			$cat);
		$tpl->assign('notuse',		$notuse);
		$tpl->assign('countall',	$count[0]);
	}

	/**
	 * News management page
	 * 
	 * Displays and manages news articles. Supports filtering by category.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 */
	private function _Page2(): void
	{
		global $tpl, $db;

		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete')
		{
			$db->Query('Delete FROM {pre}mod_feednews_news Where id=?',
				(int) $_REQUEST['id']);
		}

		$rss = [];
		if(isset($_REQUEST['cat'])){
			$q			= '\'%' . $db->Escape($_REQUEST['cat']) . '%\'';
			$res = $db->Query('SELECT news.*, news.rss AS rss FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE news.category LIKE ' . $q . ' ORDER by datum DESC');
		} else {
			$res = $db->Query('SELECT news.*, news.rss AS rss FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss ORDER by datum DESC');
		}
		while($row = $res->FetchArray())
		{
			$rss[] = $row;
		}
		$res->Free();

		$tpl->assign('rss', $rss);
	}

	/**
	 * News creation and editing page
	 * 
	 * Handles creation of new news articles and editing of existing ones.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 */
	private function _Page3(): void
	{
		global $tpl, $db;

		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save')
		{
			$res = $db->Query('INSERT INTO {pre}mod_feednews_news(rss, title, text, link, datum, category, picture) VALUES(?,?,?,?,?,?,?)', 
				(int)$_REQUEST['rss'],
				$_REQUEST['title'],
				$_REQUEST['text'],
				$_REQUEST['link'],
				$_REQUEST['datum'],
				$_REQUEST['category'],
				$_REQUEST['picture']);
			$res->Free();
		}
		if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'update')
		{
			$db->Query('UPDATE {pre}mod_feednews_news SET title=?,text=?,link=?,category=?,picture=? WHERE id=?',
				$_REQUEST['title'],
				$_REQUEST['text'],
				$_REQUEST['link'],
				$_REQUEST['category'],
				$_REQUEST['picture'],
				(int) $_REQUEST['id']);
		}
		if(isset($_REQUEST['id']))
		{
			$res = $db->Query('SELECT * FROM {pre}mod_feednews_news WHERE id=?', 
				(int) $_REQUEST['id']);
			$rss = $res->FetchArray();
			$res->Free();

			$tpl->assign('rss', $rss);
			$tpl->assign('id', true);
		} else {
			$tpl->assign('id', false);
		}
	}

	/**
	 * Check if widget is suitable for given context
	 * 
	 * @param string $for Context for widget
	 * @return bool Always returns false
	 */
	public function isWidgetSuitable(string $for): bool
	{
		return false;
	}

	/**
	 * Render widget content
	 * 
	 * Displays RSS news in widget format.
	 * 
	 * @return bool True on success
	 * @global object $tpl Template engine
	 */
	public function renderWidget(): bool
	{
		global $tpl;

		$link = "https://www.b1gmail.net/rss";
		if(!isset($_SERVER['HTTPS'])) {
			$link = "http://www.b1gmail.net/rss";
		}

		$news = "";
		$show = 5;

		$rss=simplexml_load_file($link);
		for($z=0;$z<=$show;$z++)
		{
			$news[$z]["titel"]=$this->_utf8($rss->channel[0]->item[$z]->title);
			$news[$z]["description"]=$this->_utf8($rss->channel[0]->item[$z]->description);
			$news[$z]["link"]=$this->_utf8($rss->channel[0]->item[$z]->link);
			$news[$z]["id"]=$z;
		}

		$tpl->assign('wreckedmailnews', $news);		
		return true;
	}

	/**
	 * File handler for news display
	 * 
	 * Handles news display, search, and RSS feed generation.
	 * 
	 * @param string $file The file being accessed
	 * @param string $action The action being performed
	 * @return void
	 */
	public function FileHandler(string $file, string $action): void
	{
		global $tpl, $lang_user, $cacheManager, $db;

		if($file == "index.php" AND $action == "news" AND isset($_REQUEST['showrss']))
		{
			$cat			= "nachrichten";
			$title			= "RSS";
			$cat2			= "";

			$tpl->addJSFile('nli',			"./clientlib/overlay.js");
			$tpl->addJSFile('nli',			"./plugins/js/feednews.js");

			$tpl->addCSSFile('nli',			"./plugins/css/feednews.css");

			$tpl->assign('cat',				$cat);
			$tpl->assign('cat2',			$cat2);
			$tpl->assign('pageTitle',		$title);
			$tpl->assign('languageList',	GetAvailableLanguages());

			$tpl->assign('page',			$this->_templatePath('feednews.showrss.tpl'));
			$tpl->display('nli/index.tpl');
			exit();
		}

		if($file == "index.php" AND $action == "news" AND (isset($_REQUEST['do']) AND $_REQUEST['do']=="getpic") AND isset($_REQUEST['l']))
		{
			$file_name		= $_REQUEST['l'];
			$file_extension = strtolower(substr(strrchr($file_name,"."),1));
			switch($file_extension) {
				case "jpg": $ctype="image/jpg"; break;
				case "jpeg": $ctype="image/jpeg"; break;
				case "png": $ctype="image/png"; break;
				case "gif": $ctype="image/gif"; break;
			}

			header("Content-Type: ".$ctype);
			readfile($file_name);
			exit();
		}

		if($file == "index.php" AND $action == "news" AND (!isset($_REQUEST['link']) OR $_REQUEST['link']=="") AND (!isset($_REQUEST['cat']) OR $_REQUEST['cat']=="")  AND (!isset($_REQUEST['id']) OR $_REQUEST['id']=="") AND (!isset($_REQUEST['s']) OR $_REQUEST['s']=="") AND (!isset($_REQUEST['c']) OR $_REQUEST['c']==""))
		{
			$cat			= "nachrichten";
			$title			= "Nachrichten";
			$cat2			= "";
					
			$perPage		= max(1, isset($_REQUEST['perPage']) ? (int)$_REQUEST['perPage'] : 25);
			
			$nocache			= false;
			if($perPage == 25 && (!isset($_REQUEST['page']) OR $_REQUEST['page']==1)) {
				$news 			= $cacheManager->Get('feednews_all');
				if ($news === false) {
					$nocache	= false;
				} else {
					$nocache	= true;
				}
			}
			$res			= $db->Query('SELECT COUNT(*) FROM {pre}mod_feednews_news');
			list($newsCount)= $res->FetchArray(MYSQL_NUM);
			$res->Free();
			$pageCount 		= ceil($newsCount / $perPage);
			$pageNo 		= isset($_REQUEST['page']) ? max(1, min($pageCount, (int)$_REQUEST['page'])) : 1;
			if(!$nocache) {
				$startPos 		= max(0, min($perPage*($pageNo-1), $newsCount));
				$res			= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss Order by datum DESC LIMIT ' . $startPos . ',' . $perPage);
			}
		} else if($file == "index.php" AND $action == "news" AND (isset($_REQUEST['cat']) AND $_REQUEST['cat']!= "")) {
			// Kategorie
			$cat			= $cat2	= strip_tags($_REQUEST['cat']);
			$title			= ucfirst($cat);
			$q				= '\'%' . $db->Escape($_REQUEST['cat']) . '%\'';
			
			$perPage		= max(1, isset($_REQUEST['perPage']) ? (int)$_REQUEST['perPage'] : 30);
			$res			= $db->Query('SELECT COUNT(*) FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE news.category LIKE ' . $q);
			list($newsCount)= $res->FetchArray(MYSQL_NUM);
			$res->Free();
			$pageCount 		= ceil($newsCount / $perPage);
			$pageNo 		= isset($_REQUEST['page']) ? max(1, min($pageCount, (int)$_REQUEST['page'])) : 1;
			$startPos 		= max(0, min($perPage*($pageNo-1), $newsCount));
			
			$res			= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE news.category LIKE ' . $q . ' Order by datum DESC LIMIT ' . $startPos . ',' . $perPage);
		} else if($file == "index.php" AND $action == "news" AND isset($_REQUEST['c'])) {
			// RSS absender
			$cat		= "nachrichten";
			$title		= "Nachrichten von ";

			$perPage		= max(1, isset($_REQUEST['perPage']) ? (int)$_REQUEST['perPage'] : 30);
			$res			= $db->Query('SELECT COUNT(*), rss.title FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE rss.id = ?', $_REQUEST['c']);
			list($newsCount, $title2)= $res->FetchArray(MYSQL_NUM);
			$res->Free();
			$pageCount 		= ceil($newsCount / $perPage);
			$pageNo 		= isset($_REQUEST['page']) ? max(1, min($pageCount, (int)$_REQUEST['page'])) : 1;
			$startPos 		= max(0, min($perPage*($pageNo-1), $newsCount));
			$title			.= $title2;

			$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE rss.id = ? Order by datum DESC  LIMIT ' . $startPos . ',' . $perPage, $_REQUEST['c']);
			
			$tpl->assign('c',				 strip_tags($_REQUEST['c']));
		} else if($file == "index.php" AND $action == "news" AND isset($_REQUEST['s'])) {
			$cat		= "suche";
			$title		= "Suche: ".strip_tags($_REQUEST['s']);
			$q			= '\'%' . $db->Escape($_REQUEST['s']) . '%\'';

			$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE news.category LIKE ' . $q . ' Order by datum DESC Limit 25');
			if($res->RowCount() <= 1) {
				$res->Free();
				$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE rss.title LIKE ' . $q . ' Order by datum DESC Limit 25');
				if($res->RowCount() <= 1) {
					$res->Free();
					$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopictur, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss WHERE news.title LIKE ' . $q . ' Order by datum DESC Limit 25');
				}
			}
			$tpl->assign('suche', 			true);
		} else if($file == "index.php" AND $action == "news" AND isset($_REQUEST['id'])) {
			$tmp			= $this->getNewsByID($_REQUEST['id']);
			$cat			= $cat2	= strtolower(strip_tags($tmp['category']));
			$title			= ucfirst($cat);

			$singlenews		= true;
			
			$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss Where news.id=?', $_REQUEST['id']);
			$nocache		= false;
		} else if($file == "index.php" AND $action == "news" AND isset($_REQUEST['link'])) {
			$tpl->addCSSFile('nli',			"./plugins/css/feednews.css");
			$tpl->assign('pageTitle',		$lang_user['feednews_redirect']);
			$tpl->assign('link',			strip_tags($_REQUEST['link']));
			
			$tpl->assign('languageList',	GetAvailableLanguages());
			$tpl->assign('page',			$this->_templatePath('feednews.redirect.tpl'));
			$tpl->display('nli/index.tpl');
			exit();
		}
		if($file == "index.php" AND $action == "news")
		{
			$emtpy 		= false;
			if($nocache) {
				foreach ($news as $key => $value) {
					$news[$key]['datum']	= $this->human_time_diff($value['datum']);
				}
			} else {
				$news		= array();
				if($res->RowCount() < 1) {
					$emtpy 	= true;
				} else {
					while(($row = $res->FetchArray(MYSQL_ASSOC)) !== false) {
						$offset 			= date("Z");
						$row['rssdatum']	= date("D, j M Y H:i:s ", ($row['datum']-$offset)).'GMT';
						$row['datum']		= $this->human_time_diff($row['datum']);
						$news[]				= $row;
					}
					$res->Free();
				}
			}
			$tpl->assign('empty', 			$emtpy);
			
			$tpl->addJSFile('nli',			"./clientlib/overlay.js");
			$tpl->addJSFile('nli',			"./plugins/js/feednews.js");

			$tpl->addCSSFile('nli',			"./plugins/css/feednews.css");

			$tpl->assign('cat',				$cat);
			$tpl->assign('cat2',			$cat2);

			$tpl->assign('pageTitle',		$title);
			$tpl->assign('news',			$news);

			$tpl->assign('pageNo', 			$pageNo);
			$tpl->assign('perPage', 		$perPage);
			$tpl->assign('pageCount', 		$pageCount);

			$tpl->assign('languageList',	GetAvailableLanguages());

			if(isset($_REQUEST['rss']) && $_REQUEST['rss']) {
				header("Content-type: application/rss+xml");

				$rsscat		= $cat."/";
				if($cat == "nachrichten")
					$rsscat	= "";

				$tpl->assign('http_prefix', 	isset($_SERVER['HTTPS'])?'https':'http');
				$tpl->assign('rsscat',			$rsscat);

				$tpl->display($this->_templatePath('feednews.news.rss.tpl'));
				exit();
			} else if($singlenews) {
				$tpl->addJSFile('nli',			"http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js");
				$tpl->addJSFile('nli',			"./plugins/js/feednews.tweet.js");

				//$tpl->addCSSFile('nli',			"./plugins/css/feednews.tweet.css");
				$tpl->assign('page',			$this->_templatePath('feednews.news.single.tpl'));
				$tpl->display('nli/index.tpl');
				exit();
			} else {
				$tpl->assign('page',			$this->_templatePath('feednews.news.tpl'));
				$tpl->display('nli/index.tpl');
				exit();
			}
		}
	}

	/**
	 * Calculate human-readable time difference
	 * 
	 * @param int $from Start timestamp
	 * @param int $to End timestamp (defaults to current time)
	 * @return string Human-readable time difference
	 * @global array $lang_user Language variables
	 */
	private function human_time_diff(int $from, int $to = 0): string
	{
		global $lang_user;

		if($to === 0)
			$to = time();

		$diff = (int) abs($to - $from);

		if ($diff <= 3600) {
			$mins = round($diff / 60);
			if ($mins <= 1) {
				$mins = 1;
			}
			$since = $mins." ".$lang_user['minutes'];
		} else if (($diff <= 86400) && ($diff > 3600)) {
			$hours = round($diff / 3600);
			if ($hours <= 1) {
				$hours = 1;
			}
			$since = $hours." ".$lang_user['hours'];
		} elseif ($diff >= 86400) {
			$days = round($diff / 86400);
			if ($days <= 1) {
				$days = 1;
			}
			$since = $days." ".$lang_user['days'];
		}
		return $since;
	}
	
	/**
	 * Get news article by ID
	 * 
	 * @param int $id News article ID
	 * @return array|false News article data or false if not found
	 */
	private function getNewsByID(int $id): array|false
	{
		global $db;
		
		$res		= $db->Query('SELECT news.*, rss.title as rsstitle, rss.nopicture as nopicture, rss.nolayer as nolayer FROM {pre}mod_feednews_news AS news INNER JOIN {pre}mod_feednews_rss AS rss On rss.id = news.rss Where news.id=?', $id);
		$rss = $res->FetchArray();
		$res->Free();	
		return $rss;
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
$plugins->registerPlugin('feednews');
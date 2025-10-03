<?php
declare(strict_types=1);

/**
 * TV Spielfilm Plugin
 * 
 * This plugin displays TV program information from TV Spielfilm RSS feeds.
 * Users can configure different TV program categories and the number of entries to display.
 * 
 * @version 2.0.0
 * @since PHP 8.3
 * @license GPL
 */
class widgettvspielfilm extends BMPlugin
{
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
		$this->pluginName 			= 'TV Spielfilm';
		$this->pluginVersion 		= '2.0.0';
		$this->pluginAuthor 		= 'Peter Michalk';

		$this->name					= $this->pluginName;
		$this->version				= $this->pluginVersion;
		$this->designedfor			= '7.3.0';
		$this->type					= BMPLUGIN_WIDGET;

		$this->author				= $this->pluginAuthor;

		$this->admin_pages			= false;

		$this->widgetTitle			= $this->pluginName;
		$this->widgetTemplate		= 'widget.tvspielfilm.tpl';
		$this->widgetIcon			= 'widget.tvspielfilm.icon.png';

		$this->widgetPrefs 			= true;
		$this->widgetPrefsWidth		= 300;
		$this->widgetPrefsHeight	= 150;
	}

	/**
	 * Check if widget is suitable for the given context
	 * 
	 * @param mixed $for Context to check suitability for
	 * @return bool Always returns true as this widget is suitable for all contexts
	 */
	public function isWidgetSuitable($for): bool
	{
		return true;
	}

	/**
	 * Plugin installation routine
	 * 
	 * @return bool Always returns true on successful installation
	 */
	public function Install(): bool
	{
		PutLog('Plugin "' . $this->name . ' - ' . $this->version . '" was successfully installed.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Plugin uninstallation routine
	 * 
	 * @return bool Always returns true on successful uninstallation
	 */
	public function Uninstall(): bool
	{
		PutLog('Plugin "' . $this->name . ' - ' . $this->version . '" was successfully uninstalled.', PRIO_PLUGIN, __FILE__, __LINE__);
		return true;
	}

	/**
	 * Render the widget with TV program information
	 * 
	 * @return bool Returns true on successful rendering
	 */
	public function renderWidget(): bool
	{
		global $tpl, $thisUser;

		if (!class_exists('BMHTTP')) {
			include(B1GMAIL_DIR . 'serverlib/http.class.php');
		}

		$news = [];
		$tvProgram = $thisUser->getPref('tvspielfilm');
		$showCount = $thisUser->getPref('tvspielfilm_number');
		
		if (!$showCount) {
			$showCount = 7;
		}
		
		$showDescription = true;
		
		// Determine RSS feed URL based on user preference
		$feedUrl = match ($tvProgram) {
			'tvjetzt' 	=> 'http://www.tvspielfilm.de/tv-programm/rss/jetzt.xml',
			'tv2015' 	=> 'http://www.tvspielfilm.de/tv-programm/rss/heute2015.xml',
			'tv2200' 	=> 'http://www.tvspielfilm.de/tv-programm/rss/heute2200.xml',
			'tvtipps' 	=> 'http://www.tvspielfilm.de/tv-programm/rss/tipps.xml',
			default 	=> 'http://www.tvspielfilm.de/tv-programm/rss/tipps.xml'
		};

		try {
			$http = _new('BMHTTP', [$feedUrl]);
			$result = $http->DownloadToString();
			$rss = simplexml_load_string($result);
			
			if ($rss === false || empty($rss) || count($rss) !== 1) {
				$tpl->assign('tvspielfilm_error', true);
				return true;
			}

			// Process RSS items
			for ($i = 0; $i < $showCount; $i++) {
				if (empty($rss->channel[0]->item[$i])) {
					break;
				}
				
				$enclosure = $rss->channel[0]->item[$i]->enclosure;
				$imageUrl = empty($enclosure['url']) 
					? './plugins/templates/images/widget.tvspielfilm.nopic.png'
					: $this->convertToUtf8((string)$enclosure['url']);

				$news[$i] = [
					'titel' 		=> $this->convertToUtf8((string)$rss->channel[0]->item[$i]->title),
					'description' 	=> $this->convertToUtf8((string)$rss->channel[0]->item[$i]->description),
					'pic' 			=> $imageUrl,
					'link' 			=> $this->convertToUtf8((string)$rss->channel[0]->item[$i]->link),
					'id' 			=> $i
				];
			}

			$tpl->assign('showdes', 		$showDescription);
			$tpl->assign('tvspielfilm', 	$news);
			
		} catch (Exception $e) {
			PutLog('TV Spielfilm Plugin Error: ' . $e->getMessage(), PRIO_ERROR, __FILE__, __LINE__);
			$tpl->assign('tvspielfilm_error', true);
		}
		
		return true;
	}

	/**
	 * Render widget preferences interface
	 * 
	 * @return void
	 */
	public function renderWidgetPrefs(): void
	{
		global $tpl, $thisUser;

		if (!isset($_REQUEST['save'])) {
			$tpl->assign('tvspielfilm', 		$thisUser->getPref('tvspielfilm'));
			$tpl->assign('tvspielfilm_number',	$thisUser->getPref('tvspielfilm_number'));
			$tpl->display($this->_templatePath('widget.tvspielfilm.prefs.tpl'));
		} else {
			$thisUser->setPref('tvspielfilm',			$_REQUEST['tvspielfilm']);
			$thisUser->setPref('tvspielfilm_number',	$_REQUEST['tvspielfilm_number']);
			$this->_closeWidgetPrefs();
		}
	}

	/**
	 * Convert text to UTF-8 if needed
	 * 
	 * @param string $text Text to convert
	 * @return string Converted text
	 */
	private function convertToUtf8(string $text): string
	{
		global $currentCharset;

		if (strtolower($currentCharset) !== 'utf-8') {
			return utf8_decode($text);
		}
		return $text;
	}
}
$plugins->registerPlugin('tvspielfilm');
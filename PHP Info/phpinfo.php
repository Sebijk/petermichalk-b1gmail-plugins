<?php
declare(strict_types=1);

/**
 * PHP Info Plugin
 * 
 * Displays phpinfo() in a beautifully formatted way within the admin area.
 * Shows comprehensive PHP configuration information for system administrators.
 * 
 * @version 1.1.0
 * @since PHP 8.2
 * @license GPL
 */
class phpinfo extends BMPlugin 
{
	/**
	 * Plugin constants
	 */
	private const PLUGIN_NAME 			= 'PHP Info';
	private const PLUGIN_VERSION 		= '1.1.0';
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
		$this->name					= self::PLUGIN_NAME;
		$this->version				= self::PLUGIN_VERSION;
		$this->designedfor			= self::PLUGIN_DESIGNEDFOR;
		$this->type					= BMPLUGIN_DEFAULT;

		$this->author				= self::PLUGIN_AUTHOR;

		$this->admin_pages			= true;
		$this->admin_page_title		= self::PLUGIN_NAME;
		$this->admin_page_icon		= 'phpinfo_icon.png';
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
				'title'		=> $lang_admin['overview'],
				'link'		=> $this->_adminLink() . '&action=' . self::ADMIN_PAGE1 . '&',
				'active'	=> $action === self::ADMIN_PAGE1,
				'icon'		=> '../plugins/templates/images/phpinfo_logo.png'
			]
		];

		$tpl->assign('tabs', $tabs);

		// Plugin call with action
		if($action === self::ADMIN_PAGE1) {
			$tpl->assign('page', $this->_templatePath('phpinfo.admin.tpl'));
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
		$lang_admin['phpinfo_name']				= 'PHP Info';
		$lang_admin['phpinfo_text']				= 'Zeigt PHP-Konfigurationsinformationen in schön formatierter Form im Admin-Bereich an';
		$lang_admin['phpinfo_show']				= 'Anzeigen';
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
	 * Main function for PHP Info display
	 * 
	 * This method captures phpinfo() output and formats it for display
	 * in the admin template. It processes the raw phpinfo output and
	 * creates a clean, styled version.
	 * 
	 * @return void
	 * @global object $tpl Template engine
	 * @global array $lang_admin Language variables for admin area
	 */
	private function _Page1(): void
	{
		global $tpl, $lang_admin;

		// Check if user wants to show PHP Info
		if(($_REQUEST['do'] ?? '') === 'show') {
			$tpl->assign('show_phpinfo', true);
			
			// Capture phpinfo() output
			ob_start();
			phpinfo();
			$phpinfo_content = ob_get_clean();
			
			// Clean and format the phpinfo output
			$formatted_phpinfo = $this->_formatPhpInfo($phpinfo_content);
			$tpl->assign('phpinfo_content', $formatted_phpinfo);
		}
	}

	/**
	 * Format phpinfo output for better display
	 * 
	 * Cleans up the raw phpinfo HTML output and applies custom styling
	 * to make it more readable and consistent with the admin interface.
	 * 
	 * @param string $content Raw phpinfo HTML content
	 * @return string Formatted and cleaned phpinfo content
	 */
	private function _formatPhpInfo(string $content): string
	{
		// Remove the default phpinfo styling
		$content = preg_replace('/<style.*?>.*?<\/style>/s', '', $content);
		
		// Remove the body tag and its attributes
		$content = preg_replace('/<body[^>]*>/', '', $content);
		$content = str_replace('</body>', '', $content);
		
		// Remove the html tag and its attributes
		$content = preg_replace('/<html[^>]*>/', '', $content);
		$content = str_replace('</html>', '', $content);
		
		// Remove the head section
		$content = preg_replace('/<head>.*?<\/head>/s', '', $content);
		
		// Add custom CSS for better styling
		$custom_css = '
		<style>
		.phpinfo-container {
			font-family: "Lucida Console", "Courier New", monospace;
			font-size: 12px;
			line-height: 1.4;
			background-color: #f8f9fa;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		
		.phpinfo h1 {
			color: #2c3e50;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 15px;
			margin: -20px -20px 20px -20px;
			border-radius: 8px 8px 0 0;
			font-size: 18px;
			font-weight: bold;
			text-align: center;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		
		.phpinfo h2 {
			color: #34495e;
			background-color: #ecf0f1;
			padding: 10px 15px;
			margin: 20px 0 10px 0;
			border-left: 4px solid #3498db;
			border-radius: 4px;
			font-size: 14px;
			font-weight: bold;
		}
		
		.phpinfo table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 20px;
			background-color: white;
			border-radius: 6px;
			overflow: hidden;
			box-shadow: 0 1px 3px rgba(0,0,0,0.1);
		}
		
		.phpinfo th {
			background-color: #34495e;
			color: white;
			padding: 12px 15px;
			text-align: left;
			font-weight: bold;
			border-bottom: 2px solid #2c3e50;
		}
		
		.phpinfo td {
			padding: 10px 15px;
			border-bottom: 1px solid #ecf0f1;
			vertical-align: top;
		}
		
		.phpinfo tr:nth-child(even) {
			background-color: #f8f9fa;
		}
		
		.phpinfo tr:hover {
			background-color: #e3f2fd;
		}
		
		.phpinfo .e {
			background-color: #f8f9fa;
			font-weight: bold;
			width: 30%;
			color: #2c3e50;
		}
		
		.phpinfo .v {
			background-color: white;
			color: #34495e;
			word-break: break-all;
		}
		
		.phpinfo .h {
			background-color: #3498db;
			color: white;
			font-weight: bold;
			text-align: center;
		}
		
		.phpinfo a {
			color: #3498db;
			text-decoration: none;
		}
		
		.phpinfo a:hover {
			color: #2980b9;
			text-decoration: underline;
		}
		
		.phpinfo .center {
			text-align: center;
		}
		
		.phpinfo .small {
			font-size: 11px;
		}
		
		.phpinfo hr {
			border: none;
			height: 2px;
			background: linear-gradient(90deg, #3498db, #e74c3c, #f39c12, #27ae60);
			margin: 20px 0;
		}
		
		.phpinfo-container::-webkit-scrollbar {
			width: 8px;
		}
		
		.phpinfo-container::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 4px;
		}
		
		.phpinfo-container::-webkit-scrollbar-thumb {
			background: #888;
			border-radius: 4px;
		}
		
		.phpinfo-container::-webkit-scrollbar-thumb:hover {
			background: #555;
		}
		</style>';
		
		// Wrap content in our custom container
		$formatted_content = '<div class="phpinfo-container">' . $custom_css . $content . '</div>';
		
		return $formatted_content;
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
$plugins->registerPlugin('phpinfo');
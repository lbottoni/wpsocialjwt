<?php
/**
 * @package social rest jwt auth
 */

/**
 * Plugin Name: Social Jwt Authentication
 * Plugin URI:
 * Description: Users Rest Api authentication with fb/google token
 * Version: 1.0.0
 * Author: Luca Bottoni
 * Author URI: https://lucabottoni.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace WPSOCIALJWT;
defined("ABSPATH") or die("No script kiddies please!");
define("SOCIALJWT_VERSION", "1.0.0");
require_once plugin_dir_path(__FILE__) . "vendor/autoload.php";
require_once plugin_dir_path(__FILE__) . "autoload.php";
require_once __DIR__ . "/config.php";

use WPSOCIALJWT\Classes\FacebookJWT;


class SocialJWT
	{

	protected $isactive = false;
	public $fb;
	protected $google;
	public static $domain = "wpsocialjwt";
	protected $menu_slug;
	protected $submenu_slug_fb;
	protected $submenu_slug_gp;
	public $menu_position = 2;
	protected $wp;

	public function __construct()
		{
			$this->wp = $GLOBALS["wp"];
			$this->isactive = get_option(__NAMESPACE__ . "_active");
			if ($this->isactive)
				{
				//attivo i social class
				$this->fb = new FacebookJWT(FB_APP_ID, FB_APP_SECRET);
				//aggiungo il menu al pannello admin
				$this->add_actions();
				$this->init();

				}
		}

	/**
	 * crea le azioni
	 */
	protected function add_actions()
		{
			add_action("admin_menu", array($this, "add_admin_menu"));
			add_action("plugins_loaded", array($this, "init"));
			add_action("admin_init", array($this, "register_settings"));
		}

	public function register_settings()
		{
			$this->fb->register_settings();
		}
	

	/**
	 * carica la lingua
	 */
	public function init()
		{
			$lang = (defined(WPLANG)) ? WPLANG : "it_IT";
			load_plugin_textdomain(self::$domain, false, basename(dirname(__FILE__)) . "/lang");
			$this->menu_slug = self::$domain . "_menu";
		}

	public function add_admin_menu()
		{
			$capability = "";
			$menu_slug = "";
			$function = null;
			$icon_url = "";
			$position = null;

			add_menu_page(__("Menu Social JWT Title", self::$domain),
				__("Menu Social JWT Title", self::$domain),
				"manage_options",
				$this->menu_slug,
				null,
				$icon_url,
				$this->menu_position);

			add_submenu_page($this->menu_slug,
				__("SubMenu Facebook JWT", self::$domain),
				__("SubMenu Facebook JWT", self::$domain),
				"manage_options",
				$this->submenu_slug_fb,
				array($this, "view_fb"));
			add_submenu_page($this->menu_slug,
				__("SubMenu Google plus JWT", self::$domain),
				__("SubMenu Google plus JWT", self::$domain),
				"manage_options",
				$this->submenu_slug_gp,
				array($this, "view_fb"));
		}

	public function view_fb()
		{
			include_once "view/admin_fb.php";
		}

	}


register_activation_hook(__FILE__,
	function () {
		update_option(__NAMESPACE__ . "_active", true);
	});

register_deactivation_hook(__FILE__,
	function () {
		update_option(__NAMESPACE__ . "_active", false);
	});
new SocialJWT();
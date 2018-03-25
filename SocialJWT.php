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
	protected $domain = "wpsocialjwt";
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

	/**
	 *  "type"    (string) The type of data associated with this setting. Valid values are "string", "boolean", "integer", and "number
	 * "description"    (string) A description of the data attached to this setting.
	 * "sanitize_callback"    (callable) A callback function that sanitizes the option"s value.
	 * "show_in_rest"    (bool) Whether data associated with this setting should be included in the REST API.
	 * "default"    (mixed) Default value when calling get_option().
	 */

	public function register_settings()
		{
			add_settings_section("id_setting_section_fb", // ID per la sezione e i campi
				"Configurazione rest endpoint facebook",
				array($this, "section_callback_function"),
				"fb_page" // Sezione sotto "Settings/Impostazioni" dove inserirla
			);

			if(get_option("{$this->domain}_endpoint_fb")==false){
				update_option("{$this->domain}_endpoint_fb","{$this->fb->routeurl}");
			}
			//$endpoint= (get_option("{$this->domain}_endpoint_fb"))?"{$this->domain}_endpoint_fb":get_option( 'siteurl' )."/{$this->fb->namespace}/{$this->fb->routeurl}";
			//◙◙◙◙◙◙◙◙ endpoint ◙◙◙◙◙◙◙◙
			add_settings_field("{$this->domain}_endpoint_fb",
				"Facebook rest end point",
				array($this,"field_callback_endpoint_fb"),
				"fb_page",
				"id_setting_section_fb");
			//◙◙◙◙◙◙◙◙ appid_fb ◙◙◙◙◙◙◙◙
			add_settings_field("{$this->domain}_appid_fb",
				"Facebook APP ID",
				array($this,"field_callback_appid_fb"),
				"fb_page",
				"id_setting_section_fb");
			//◙◙◙◙◙◙◙◙ appsecret_fb ◙◙◙◙◙◙◙◙
			add_settings_field("{$this->domain}_appsecret_fb",
				"Facebook APP SECRET",
				array($this,"field_callback_appsecret_fb"),
				"fb_page",
				"id_setting_section_fb");
			//------------------------------------------------
			register_setting( 'fbgroup', "{$this->domain}_endpoint_fb" );			// Registra l’opzione my_test in modo che $_POST venga gestito automaticamente
			register_setting( 'fbgroup', "{$this->domain}_appid_fb");
			register_setting( 'fbgroup', "{$this->domain}_appsecret_fb");
		}
	public function field_callback_endpoint_fb()
		{
			echo "<input type=\"text\" name=\"{$this->domain}_endpoint_fb\" value=\"" . get_option("{$this->domain}_endpoint_fb") . "\" />";
		}
	public function field_callback_appid_fb()
		{
			echo "<input type=\"text\" name=\"{$this->domain}_appid_fb\" value=\"" . get_option("{$this->domain}_appid_fb") . "\" />";
		}
	public function field_callback_appsecret_fb()
		{
			echo "<input type=\"text\" name=\"{$this->domain}_appsecret_fb\" value=\"" . get_option("{$this->domain}_appsecret_fb") . "\" />";
		}
	public function section_callback_function()
		{
			_e( "url endpoint: ".get_option( "siteurl" )."/{$this->fb->namespace}/{$this->fb->routeurl}/".get_option( "{$this->domain}_endpoint_fb" ), $this->domain);
		}

	public function _register_settings()
		{
			//registro i setting fb

			$defaults = array("type" => "string",
							  "description" => "",
							  "sanitize_callback" => null,
							  "show_in_rest" => false
			);
			register_setting("{$this->domain}_fb-group",
				"resturl"//			,array_merge($defaults,array("description"=>__("rest url descrizione",get_site_url(null,"/wp-json/wp/v2"))))
			);
			register_setting("{$this->domain}_fb-group",
				"appkey"/*, array_merge($defaults,array("description"=>__("fb_appkey")))*/);
			register_setting("{$this->domain}_fb-group",
				"appsecret"/*, array_merge($defaults,array("description"=>__("fb_appsecret")))*/);
			//setting s gplus
			//		register_setting( "{$this->domain}_gplus-group", "resturl" /*,
			//			array_merge($defaults,array("description"=>__("rest url descrizione",get_site_url(null,"/wp-json/wp/v2"))))*/
			//			);
			//		register_setting( "{$this->domain}_gplus-group", "appkey" );
			//		register_setting( "{$this->domain}_gplus-group", "appsecret" );
			register_setting("my_option_group", // Option group
				"my_option_name", // Option name
				array($this, "sanitize") // Sanitize
			);

			add_settings_section("setting_section_id", // ID
				"My Custom Settings", // Title
				function () {
					echo "hello";
				},
				"my-setting-admin" // Page
			);

			add_settings_field("id_number", // ID
				"ID Number", // Title
				function () {
					printf('<input type="ext" id="id_number" name="my_option_name[id_number]" value=" % s" />',
						isset($this->options["id_number"]) ? esc_attr($this->options["id_number"]) : "");
				},
				"my - setting - admin", // Page
				"setting_section_id" // Section
			);

			add_settings_field("title", "Title",
				function () {
					printf('<input type="text" id="title" name="my_option_name[title]" value=" % s" />',
						isset($this->options["title"]) ? esc_attr($this->options["title"]) : "");
				},
				"my - setting - admin",
			"setting_section_id"
		);
	}
	public function init()
			{
				$lang = (defined(WPLANG)) ? WPLANG : "it_IT";
				load_plugin_textdomain($this->domain, false, basename(dirname(__FILE__)) . " / lang");
				$this->menu_slug = "{
					$this->domain}_menu";
			}

	public function add_admin_menu()
			{
				$capability = "";
				$menu_slug = "";
				$function = null;
				$icon_url = "";
				$position = null;

				add_menu_page(__("Menu Social JWT Title", $this->domain),
					__("Menu Social JWT Title", $this->domain),
					"manage_options",
					$this->menu_slug,
					null,
					$icon_url,
					$this->menu_position);
//
//				add_options_page(
//					"Official Deved Options Plugin",
//					"Deved Options",
//					"manage_options",
//					$this->menu_slug."_opt",
//					function(){
//						if( !current_user_can( "manage_options" ) ) {
//
//						wp_die( "I tuoi permessi non sono sufficienti per visualizzare la pagina" );
//
//						}
//
//						echo " < p>Benvenuto in questo plugin!</p > ";
//					}
//				);


				add_submenu_page($this->menu_slug,
					__("SubMenu Facebook JWT", $this->domain),
					__("SubMenu Facebook JWT", $this->domain),
					"manage_options",
					$this->submenu_slug_fb,
					array($this, "view_fb"));
				add_submenu_page($this->menu_slug,
					__("SubMenu Google plus JWT", $this->domain),
					__("SubMenu Google plus JWT", $this->domain),
					"manage_options",
					$this->submenu_slug_gp,
					array($this, "view_fb"));
			}
	public function view_fb()
			{
				include_once "view/admin_fb.php";
			}

	}


register_activation_hook(__FILE__, function ()
	{
		update_option(__NAMESPACE__ . "_active", true);
	});

register_deactivation_hook(__FILE__, function ()
	{
		update_option(__NAMESPACE__ . "_active", false);
	});
new SocialJWT();
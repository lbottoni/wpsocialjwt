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
	protected $fb;
	protected $google;
	public function __construct()
		{
			$this->isactive = get_option(__NAMESPACE__ . "_active");
			if ($this->isactive)
				{
					//attivo i social class
					$this->fb=new FacebookJWT(FB_APP_ID,FB_APP_SECRET);
				}
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
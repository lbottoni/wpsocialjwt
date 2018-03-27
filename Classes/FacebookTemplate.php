<?php


namespace WPSOCIALJWT\Classes;

use WPSOCIALJWT\SocialJWT;

trait FacebookTemplate
	{
	protected $domain;

	public function __construc()
		{
			$this->domain = SocialJWT::$domain;
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

			if (get_option(SocialJWT::$domain . "_endpoint_fb") == false)
				{
				update_option(SocialJWT::$domain . "_endpoint_fb", "{$this->routeurl}");
				}
			//$endpoint= (get_option(SocialJWT::$domain."_endpoint_fb"))?SocialJWT::$domain."_endpoint_fb":get_option( 'siteurl' )."/{$this->namespace}/{$this->routeurl}";
			//◙◙◙◙◙◙◙◙ endpoint ◙◙◙◙◙◙◙◙
			add_settings_field(SocialJWT::$domain . "_endpoint_fb",
				"Facebook rest end point",
				array($this, "field_callback_endpoint_fb"),
				"fb_page",
				"id_setting_section_fb");
			//◙◙◙◙◙◙◙◙ appid_fb ◙◙◙◙◙◙◙◙
			add_settings_field(SocialJWT::$domain . "_appid_fb",
				"Facebook APP ID",
				array($this, "field_callback_appid_fb"),
				"fb_page",
				"id_setting_section_fb");
			//◙◙◙◙◙◙◙◙ appsecret_fb ◙◙◙◙◙◙◙◙
			add_settings_field(SocialJWT::$domain . "_appsecret_fb",
				"Facebook APP SECRET",
				array($this, "field_callback_appsecret_fb"),
				"fb_page",
				"id_setting_section_fb",
				array('label_for' => 'myprefix_setting-id'));
			//------------------------------------------------
			register_setting('fbgroup', SocialJWT::$domain . "_endpoint_fb", array($this, "sanitize_endpoint"));
			// Registra l’opzione my_test in modo che $_POST venga gestito automaticamente
			register_setting('fbgroup', SocialJWT::$domain . "_appid_fb", array($this, "sanitize_id"));
			register_setting('fbgroup', SocialJWT::$domain . "_appsecret_fb", array($this, "sanitize_secret"));
		}

	/**
	 * verifica APPID
	 *
	 * @param $data
	 * @return string
	 */
	public function sanitize_id($data){
		$data=trim($data);
		if ($data == null)
			{
			add_settings_error("fb_page", 'empty', 'APPID Cannot be empty', 'error');
			}
		return sanitize_text_field($data);
	}
	public function sanitize_secret($data){
		$data=trim($data);
		if ($data == null)
			{
			add_settings_error("fb_page", 'empty', 'APPSECRET Cannot be empty', 'error');
			}
		return sanitize_text_field($data);
	}
	public function sanitize_endpoint($data)
		{
			$data=trim($data);
			if ($data == null)
				{
				add_settings_error("fb_page", 'empty', 'endpoint Cannot be empty', 'error');
				}
			$data=(substr($data,0,1)==="/")?substr($data,1):$data;
			$data=(substr($data,-1)==="/")?substr($s,0,-1):$data;
			preg_match("/[\w\-]+(\/[\w\-]+|)+/",$data,$match);
			if($match[0]!==$data){
				add_settings_error("fb_page", 'nomatch', 'endpoint is not valid', 'error');
			}

			return sanitize_text_field($data);

		}
	public function field_callback_endpoint_fb()
		{
			echo "<input type=\"text\" name=\"" . SocialJWT::$domain . "_endpoint_fb\" value=\"" . esc_attr(get_option(SocialJWT::$domain . "_endpoint_fb")) . "\" />";
		}
	public function field_callback_appid_fb()
		{
			echo "<input type=\"text\" name=\"" . SocialJWT::$domain . "_appid_fb\" value=\"" . esc_attr(get_option(SocialJWT::$domain . "_appid_fb")) . "\" />";
		}
	public function field_callback_appsecret_fb($data)
		{
			echo "<input type=\"password\" name=\"" . SocialJWT::$domain . "_appsecret_fb\" value=\"" . esc_attr(get_option(SocialJWT::$domain . "_appsecret_fb")) . "\" />";
		}
	public function section_callback_function()
		{
			_e("url endpoint: " . get_option("siteurl") . "/{$this->namespace}/" . get_option(SocialJWT::$domain . "_endpoint_fb") . "/<b>{yourtoken_facebook}</b>",
				SocialJWT::$domain);
		}
	}
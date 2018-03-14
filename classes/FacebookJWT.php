<?php



namespace WPSOCIALJWT\classes;

use WPSOCIALJWT\Controller\FBJWT_Controller as FBJWT_Controller;


class FacebookJWT
	{

	/**
	 * https://stionic.com
	 * https://github.com/Tmeister/wp-api-jwt-auth/archive
	 *
	 * FacebookJWT constructor.
	 */
	public function __construct()
		{
			new FBJWT_Controller(FB_APP_ID,FB_APP_SECRET);
		}
	}

<?php



namespace WPSOCIALJWT\Classes;

use WPSOCIALJWT\Controller\FBJWT_Controller as FBJWT_Controller;


class FacebookJWT extends FBJWT_Controller
	{

	/**
	 * https://stionic.com
	 * https://github.com/Tmeister/wp-api-jwt-auth/archive
	 *
	 * FacebookJWT constructor.
	 */
	public function __construct($FB_APP_ID,$FB_APP_SECRET)
		{
			parent::__construct($FB_APP_ID,$FB_APP_SECRET);
			//new FBJWT_Controller(FB_APP_ID,FB_APP_SECRET);
		}
	}

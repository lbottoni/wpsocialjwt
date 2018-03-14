<?php
namespace WPSOCIALJWT\Controller;


class FBJWT_Controller extends \WP_REST_Controller
	{
	protected  $app_id=null;
	protected  $app_secret=null;
	function __construct($app_id,$app_secret)
		{
			$this->namespace = "wp/v2";
			$this->app_id=$app_id;
			$this->app_secret=$app_secret;
			add_action("rest_api_init", array($this, "register_routes"));
		}

	public function register_routes()
		{
			// register facebookjwt/token
			register_rest_route($this->namespace,
				"/facebookjwt/token/(?P<token>[\w]+)",
				array("methods" => "GET",
					  "callback" => array($this, "token"),
					  "args" => array("token" => array("required" => true,
													   "sanitize_callback" => "esc_sql",
													   "validate_callback" => function ($param, $request, $key) {
														   return is_string($param);
													   }
					  )
					  )
				));
			// array(
			//						"username" => array(
			//							"required" => true,
			//							"sanitize_callback" => "esc_sql"
			//						),
			//						"token" => array(
			//							"required" => true,
			//							"sanitize_callback" => "esc_sql"
			//						),
			//						"email" => array(
			//							"required" => true,
			//							"sanitize_callback" => "esc_sql"
			//						)
			//					),

			//				));
		}

	public function token(\WP_REST_Request $request)
		{
			$accessToken = $request->get_param("token");

			$fb = new \Facebook\Facebook(['app_id' => $this->app_id,
										  'app_secret' => $this->app_secret,
										  'default_graph_version' => 'v2.10',
										  //'default_access_token' => '{access-token}', // optional
			]);


			// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
			$helper = $fb->getRedirectLoginHelper();
			//						   $helper = $fb->getJavaScriptHelper();
			//			   $helper = $fb->getCanvasHelper();
			//			   $helper = $fb->getPageTabHelper();

			try
				{
				// Get the \Facebook\GraphNodes\GraphUser object for the current user.
				// If you provided a 'default_access_token', the '{access-token}' is optional.
				$permission = array("id",
									"cover",
									"name",
									"first_name",
									"last_name",
									"age_range",
									"link",
									"gender",
									"locale",
									"picture",
									"timezone",
									"updated_time",
									"verified",
				);
				//				$response = $fb->get('/me?fields=id,name,email', $accessToken);
				$p = implode(",", $permission);
				$response = $fb->get("/me?fields={$p}", $accessToken);

				//$accessToken = $helper->getAccessToken();
				} catch (\Facebook\Exceptions\FacebookResponseException $e)
				{
				// When Graph returns an error
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
				} catch (\Facebook\Exceptions\FacebookSDKException $e)
				{
				// When validation fails or other local issues
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
				}

			if (!isset($accessToken))
				{
				if ($helper->getError())
					{
					header('HTTP/1.0 401 Unauthorized');
					echo "Error: " . $helper->getError() . "\n";
					echo "Error Code: " . $helper->getErrorCode() . "\n";
					echo "Error Reason: " . $helper->getErrorReason() . "\n";
					echo "Error Description: " . $helper->getErrorDescription() . "\n";
					}
				else
					{
					header('HTTP/1.0 400 Bad Request');
					echo 'Bad request';
					}
				exit;
				}
			$user = $response->getGraphUser();
			return new \WP_REST_Response(["a" => 1,
										 "b" => 2,
										 "token" => $accessToken,
										 //										 "getName" => $accessToken->getValue(),
										 "helper" => $helper,
										 "response" => $response,
										 "\$user" => $user->asJson(),
			], 200);
			//return json_encode($request);
		}

	}


function prova()
	{
		echo "BBBB";
	}
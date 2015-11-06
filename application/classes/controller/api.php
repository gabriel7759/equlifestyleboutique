<?php defined('SYSPATH') or die('No direct script access.');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

class Controller_Api extends Controller {

	public $usr_token;
	public $usr_session;
	public $_session;
	public $FB_session;
	
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);

//		FacebookSession::setDefaultApplication(Kohana::$config->load('facebook.appid'), Kohana::$config->load('facebook.secret'));

	}

	public function action_register()
	{
		
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received");
		$data = NULL;

		if ($_POST)
		{
			
			$data = array_merge( (array) $data, $_POST);
			$data['regdate'] = date('Y-m-d H:i:s');
			
			// Send to seller
			$register = Model::factory('registration')->insert($data);

			if($register)
				$result = array("success"=>TRUE, "error"=>FALSE, "errordesc"=>"");
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}



}
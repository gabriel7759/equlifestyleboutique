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

			if($register) {
				
				$subject = utf8_encode('Equ Lifestyle Boutique');
				$message = '
					Hello!
					<br /><br />
					First of all, we would like to THANK YOU for signing up and becoming part of the EQU family! We are so excited to be able to launch EQU Online and provide you with an easy way to connect to the brands you love any time of day. 
					<br /><br />	
					By signing up to EQU Online you will be the first to know about new products, sales, promotions and contests both online and in store. We will be offering a broader range of products and brands online, we can’t wait to show you!
					<br /><br />	
					We promise not to send you more than one email a week, you can unsubscribe anytime.
					<br /><br />
					Welcome to the Family!
					<br /><br />
				';
				$email = Email::factory($subject, $message, 'text/html')
				->from('no-reply@equlifestyleboutique.com', 'Equ Lifestyle Boutique');
				$send = FALSE;
				$email->to($data['email'], $subject);		
				$send = TRUE;
				
				if ($send)
				{
					$email->send();
				}

				$result = array("success"=>TRUE, "error"=>FALSE, "errordesc"=>"");
			}
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}



}
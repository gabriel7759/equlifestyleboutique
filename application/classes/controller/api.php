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

		FacebookSession::setDefaultApplication(Kohana::$config->load('facebook.appid'), Kohana::$config->load('facebook.secret'));

		// Get session data
		$this->_session = Session::instance();
		$this->usr_session = $this->_session->get('bolkalk');
		$this->FB_session = $this->_session->get('FBbolkalk');
		
		// Force cookie and session reset if session not found in database
		if ($this->usr_session AND is_null($this->usr_session['data']))
		{
			$sess = Model::factory("Users")->fetch_session_data(array(
				'token' => Cookie::get('bolkalk')
			));

			if ( ! $sess)
			{
				$this->usr_session = NULL;
				Cookie::set('bolkalk', NULL);
			}
		}

		if(!$this->usr_session){
			// no session, check cookie
			$this->usr_token = Cookie::get('bolkalk');
			if(!$this->usr_token){
				$this->usr_token = Text::random('distinct', 16);
				Cookie::set('bolkalk', $this->usr_token);
				Model::factory("Users")->register_session(array('token'=>$this->usr_token));
			}
			$sess = Model::factory("Users")->fetch_session_data(array('token'=>$this->usr_token));
			
			$this->usr_session = array('logged'=>false, 'data'=>$sess);
			$this->_session->set('bolkalk', $this->usr_session);

		} else {
			$this->usr_token = Cookie::get('bolkalk');
		}
		
//		$this->usr_token = $this->usr_session['data']['token'];

	}

	public function action_signup()
	{
		
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received");
		$data = NULL;

		if ($_POST)
		{
			$data = array_merge( (array) $data, $_POST);
			
			// Check if already registered
			$check = Model::factory('Users')->fetch_by_email($data['email']);
			
			if($check){
				$result['success'] = FALSE;
				$result['error'] = TRUE;
				$result['errordesc'] = "E-postadressen er allerede registrert";
			} else {
				$userId = Model::factory('Users')->insert($data);
				
				Model::factory('Users')->update_session_data(array('id_user'=>$userId, 'id'=>$this->usr_session['data']['id']));
				$sess = Model::factory("Users")->fetch_session_data(array('id'=>$this->usr_session['data']['id']));
				$this->usr_session = array('logged'=>true, 'data'=>$sess);
				$this->_session->set('bolkalk', $this->usr_session);

				if(!$data['remember']){
					/*
					IF user didn't check the "remember me" option we keep the token and userdata in session but we need to reset the cookie
					in order to avoid autologin when user returns
					If user decides to "remember" session we just keep the current token in cookie to autologin user when he returns
					*/
					$this->usr_token = Text::random('distinct', 16);
					Cookie::set('bolkalk', $this->usr_token);
					Model::factory("Users")->register_session(array('token'=>$this->usr_token));
				}
				
				$result['success'] = TRUE;
				$result['error'] = FALSE;
				$result['errordesc'] = "";
			}
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}

	public function action_signupfb()
	{
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received");

		if($this->FB_session){
			$session = new FacebookSession($this->FB_session['token']);
			if (!$session->Validate(Kohana::$config->load('facebook.appid'), Kohana::$config->load('facebook.secret'))) {
				unset($session);
			}
		} else {
			$fbsession = new FacebookSession($_POST['accessToken']);
			try {
				$request = new FacebookRequest($fbsession, 'GET', '/me');
				$response = $request->execute();
				$object = $response->getGraphObject();
				$this->FB_session = array(
					"name"=>$object->getProperty('name'),
					"fb_name"=>$object->getProperty('name'),
					"fb_id"=>$object->getProperty('id'),
					"token"=>$this->FB_session
				);
				
				$this->_session->set('FBbolkalk', $this->FB_session);
				
				// Check if already registered
				$signin = Model::factory('Users')->signin(array("fb_id"=>$this->FB_session['fb_id']));

				if($signin){
					// already registered, fetch data
					$sess = Model::factory("Users")->fetch_session_data(array('id_user'=>$signin));
					$this->usr_session = array('logged'=>true, 'data'=>$sess);
					$this->_session->set('bolkalk', $this->usr_session);

					// Here we need to check if the current session has saved configurations to merge them
					// First fetch old session data
					$old = Model::factory('Users')->fetch_session_data(array('token'=>$this->usr_token));
				
					// Update configs from old to new
					$updateConfigs = Model::factory('Houses')->mergeConfigs(array('from_id'=>$old['id'], 'to_id'=>$this->usr_session['data']['id']));
				
					$result['success'] = TRUE;
					$result['error'] = FALSE;
					$result['errordesc'] = "";
				
				} else {
					// Not registered
					$userId = Model::factory('Users')->insert($this->FB_session);
				
					Model::factory('Users')->update_session_data(array('id_user'=>$userId, 'id'=>$this->usr_session['data']['id']));
					$sess = Model::factory("Users")->fetch_session_data(array('id'=>$this->usr_session['data']['id']));
					$this->usr_session = array('logged'=>true, 'data'=>$sess);
					$this->_session->set('bolkalk', $this->usr_session);
					
					/*
					Session data is now stored in a session variable and that's what we are going to use instead of cookie
					We must reset the cookie
					*/
					$this->usr_token = Text::random('distinct', 16);
					Cookie::set('bolkalk', $this->usr_token);
					Model::factory("Users")->register_session(array('token'=>$this->usr_token));
				
					$result['success'] = TRUE;
					$result['error'] = FALSE;
					$result['errordesc'] = "";
				}


			} catch (FacebookRequestException $ex) {
				$result['success'] = TRUE;
				$result['error'] = FALSE;
				$result['errordesc'] = $ex->getMessage();
			} catch (\Exception $ex) {
				$result['success'] = TRUE;
				$result['error'] = FALSE;
				$result['errordesc'] = $ex->getMessage();
			}
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);

	}

	public function action_signin()
	{
		
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received");
		$data = NULL;

		if ($_POST)
		{
			$data = array_merge( (array) $data, $_POST);
			
			// Check if already registered
			$userId = Model::factory('Users')->signin(array("email"=>$data['email'], 'usrpwd'=>$data['passwd']));
			
			if($userId){
				$sess = Model::factory("Users")->fetch_session_data(array('id_user'=>$userId));
				$this->usr_session = array('logged'=>true, 'data'=>$sess);
				$this->_session->set('bolkalk', $this->usr_session);
				
				// Here we need to check if the current session has saved configurations to merge them
				// First fetch old session data
				$old = Model::factory('Users')->fetch_session_data(array('token'=>$this->usr_token));
				
				// Update configs from old to new
				$updateConfigs = Model::factory('Houses')->mergeConfigs(array('from_id'=>$old['id'], 'to_id'=>$this->usr_session['data']['id']));
				
				if($data['remember']){
					// Set the session cookie to the saved token
					Cookie::set('bolkalk', $this->usr_session['data']['token']);
					// Since we already merge the configs and the old session is empty we should delete it from the "sessions" table since we are storing
					// the token for the current session
					$delete = Model::factory('Users')->delete_session(array('token'=>$this->usr_token));
				}

				$result['success'] = TRUE;
				$result['error'] = FALSE;
				$result['errordesc'] = "";
			} else {
				$result['success'] = FALSE;
				$result['error'] = TRUE;
				$result['errordesc'] = "E-post er ikke registrert eller feil passord";
			}
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}

	public function action_forgot()
	{
		
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received");
		$data = NULL;

		if ($_POST)
		{
			$data = array_merge( (array) $data, $_POST);
			
			// Check if already registered
			$sent = Model::factory('Users')->forgot($data['email']);
			if($sent)
				$result = array("success"=>TRUE, "error"=>FALSE, "errordesc"=>"");
			else
				$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"Vi kan ikke finne brukerkontoen din. Vennligst kontroller at e-postadressen er den samme som du brukte ved registrering.");
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}

	public function action_sendselger()
	{
		
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received");
		$data = NULL;

		if ($_POST)
		{
			
			$data = array_merge( (array) $data, $_POST);
			
			// Send to seller
			$sent = Model::factory('Houses')->sendselger($data, $this->usr_session);

			if($sent['result'])
				$result = array("success"=>TRUE, "error"=>FALSE, "errordesc"=>"", 'to'=>$sent['to']);
			else
				$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"Det oppstod en feil ved sending til selger");
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}

	public function action_loadModel()
	{
		$result = array();
		if ($_POST)
		{
			$data = array_merge( (array) $data, $_POST);
			
			// Check if already registered
			$result = Model::factory('Houses')->fetch(array("id"=>$data['id']));
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}

	public function action_loadKommuner()
	{
		$result = array();
		if ($_GET)
		{
			$data = array_merge( (array) $data, $_GET);
			
			// Check if already registered
			$result = Model::factory('Kommune')->fetch_autocomplete(array("term"=>$data['term']));
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}
	
	public function action_saveHouse()
	{
		$result = array("success"=>FALSE, "error"=>TRUE, "errordesc"=>"No data received", 'id'=>0, 'registered'=>$this->usr_session['logged']);
		$data = NULL;
		
		if($_POST){
			$save = Model::factory('Houses')->savemyhouse(array('id_session'=>$this->usr_session['data']['id'], 'data'=>$_POST));
			if($save){
				$result = array("success"=>TRUE, "error"=>FALSE, "errordesc"=>"", 'id'=>$save, 'registered'=>$this->usr_session['logged']);
			}
		}

		$response = json_encode($result);
		
		$this->response->body($response);
	}

	public function action_ocDesc()
	{
		$result = array();
		if ($_POST)
		{
			$data = array_merge( (array) $data, $_POST);
			
			// Check if already registered
			$desc = Model::factory('Othercosts')->fetch(array("id"=>$data['id']));
			$result = array("title"=>$desc['title'], "desc"=>nl2br($desc['description']));
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}
	
	public function action_content()
	{
		$result = array();
		if ($_POST)
		{
			$data = array_merge( (array) $data, $_POST);
			
			// Check if already registered
			$desc = Model::factory('Content')->get_page(array("id"=>$data['url']));
			$result = array("id"=>$desc['id'], "title"=>$desc['title'], "content"=>$desc['content']);
		}
		
		$response = json_encode($result);
		
		$this->response->body($response);
	}


}
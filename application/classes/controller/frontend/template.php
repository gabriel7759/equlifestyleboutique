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

class Controller_Frontend_Template extends Controller_Template {
	
	public $template;
	
	protected $_directory;
	protected $_controller;
	protected $_action;
	public $usr_token;
	public $usr_session;
	public $FB_session;

	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		
		$this->_directory  = Request::$current->directory();
		$this->_controller = Request::$current->controller();
		$this->_action     = Request::$current->action();

//		FacebookSession::setDefaultApplication(Kohana::$config->load('facebook.appid'), Kohana::$config->load('facebook.secret'));

		// Get session data
		$session = Session::instance();

		
	}
	
	public function before()
	{

		// Render JSON for Ajax requests
		if (Request::current()->is_ajax())
		{
			$this->auto_render = FALSE;
			
			$this->response->headers('Content-Type', 'application/json');
			$this->response->headers('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
		}
		
		if($this->_controller == 'print')
			$this->template = 'frontend/print_template';
		else
			$this->template = 'frontend/template';
		
		parent::before();
		
		// Template variables
		if ($this->auto_render === TRUE)
		{
			


			$this->template->facebook   = Kohana::$config->load('facebook.appid');
			$this->template->analytics  = Model_Sys_Setting::value('google_analytics');
			$this->template->fb_name    = Model_Sys_Setting::value('fb_name');
			$this->template->fb_image   = 'http://equ.com/assets/files/facebook/'.Model_Sys_Setting::value('fb_image');
			$this->template->fb_caption = Model_Sys_Setting::value('fb_caption');
			$this->template->fb_desc    = Model_Sys_Setting::value('fb_description');
			$this->template->fb_type    = "website";
			$this->template->sitename    = "equ";
			$this->template->siteurl    = "http://equ.com";
			$this->template->keywords   = Model_Sys_Setting::value('keywords');
			$this->template->description= Model_Sys_Setting::value('description');
			$this->template->mobile     = $this->_mobile();
			$this->template->active_menu = "";
			$this->template->is_home    = FALSE;
			$this->template->action     = $this->_action;
			$this->template->controller = $this->_controller;

			$this->template->privacy = Model::factory('Content')->fetch(array('id'=>1));
		
//			$helper = new FacebookRedirectLoginHelper('http://equ.com/');
//			$this->template->FBloginUrl = $helper->getLoginUrl();





		}
	}
	
	
	protected function _mobile()
	{
		return (strpos(strtolower(Request::$user_agent), 'mobile') !== FALSE) ? 'mobile': '';
	}

}
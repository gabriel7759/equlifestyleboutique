<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Frontend_Homepage extends Controller_Frontend_Template {
	
	
	public function action_index()
	{
		$this->template->active_menu = 'home';
		$view = View::factory('frontend/homepage/index');
		$this->template->is_home = TRUE;
		$this->template->title = 'Homepage';
		
		$this->template->content = $view;
	}


	public function action_content()
	{
		$level1 = $this->request->param('level1');
		$level2 = $this->request->param('level2');
		$level3 = $this->request->param('level3');
		$this->template->active_menu = $level1;

		$content = Model::factory('Content')->get_page($level1, $level2, $level3);

		$view = View::factory('frontend/homepage/content')
			->set('content', $content)
			->set('active_sub', $level2)
			->set('submenu', Model::factory('Content')->get_submenu($level1));
		
		$this->template->title   = 'Content';
		$this->template->content = $view;
	}


}
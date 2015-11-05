<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Frontend_Homepage extends Controller_Frontend_Template {
	
	
	public function action_index()
	{
		$this->template->active_menu = 'home';
		$view = View::factory('frontend/homepage/index')
			->set('experiences', Model::factory('Experiences')->fetch_active());
		/*
			->set('houses', $houses)
			->set('mainhouse', $data)
			->set('incaddons', Model::factory('Incaddons')->fetch_active())
			->set('addons', Model::factory('Addons')->fetch_active())
			->set('othercosts', Model::factory('Othercosts')->fetch_active());
		*/
		$this->template->is_home = TRUE;
		$this->template->title = 'Homepage';
		
		$this->template->content = $view;
	}

	public function action_experiencias()
	{
		$this->template->active_menu = 'home';
		$view = View::factory('frontend/homepage/experiencias');
		$this->template->is_home = TRUE;
		$this->template->title = 'Homepage';
		
		$this->template->content = $view;
	}

	public function action_contact()
	{
		$this->template->active_menu = 'contact';
		$view = View::factory('frontend/homepage/contact');
		$this->template->title = 'Contacto';

		$this->template->content = $view;
	}
	public function action_nosotros()
	{
		$this->template->active_menu = 'nosotros';
		$view = View::factory('frontend/homepage/nosotros');
		$this->template->title = 'Nosotros';

		$this->template->content = $view;
	}


	public function action_notes()
	{
		$year = $this->request->param('year');
		$month = $this->request->param('month');
		$slug = $this->request->param('slug');

		$this->template->active_menu = "";

		$content = Model::factory('Experiences')->fetch_by_url($year, $month, $slug);

		$view = View::factory('frontend/homepage/notes')
			->set('content', $content);
		
		$this->template->title   = 'Content';
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
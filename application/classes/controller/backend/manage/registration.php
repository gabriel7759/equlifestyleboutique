<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Backend_Manage_Registration extends Controller_Backend_Template { 
	
	public function before()
	{
		parent::before();
		
		$this->model = new Model_Registration;
	}
	
	public function action_index()
	{
		
		$page     = Arr::get($_GET, 'page', 1);
		$order_by = Arr::get($_GET, 'order_by', 'registration.regdate');
		$sort     = Arr::get($_GET, 'sort', 'DESC');
		$status   = Arr::get($_GET, 'status', -1);
		$text     = Arr::get($_GET, 'text', '');
		$offset   = ($page - 1) * $this->items_per_page;
		$param_filter = "?page=".$page."&order_by=".$order_by."&sort=".$sort."&status=".$status."&text=".$text;
		
		$this->template->content->data = $this->model->fetch_all(array(
			'limit'    => $this->items_per_page,
			'offset'   => $offset,
			'order_by' => $order_by,
			'sort'     => $sort,
			'status'   => $status,
			'text'     => $text,
		));
		
		$pagination = Pagination::factory(array(
			'total_items'    => $this->template->content->data->found_rows(),
			'items_per_page' => $this->items_per_page,
		));
		
		$this->template->content->page       = $page;
		$this->template->content->order_by   = $order_by;
		$this->template->content->sort       = $sort;
		$this->template->content->status     = $status;
		$this->template->content->text       = $text;
		$this->template->content->param_filter = $param_filter;
		$this->template->content->page_links = $pagination->render();
	}
	
	public function action_form()
	{
		$id = Arr::get($_GET, 'id', 0);
		$data = $this->model->fetch(array(
			'id' => $id,
		));
		
		if ($_POST)
		{	
			$data  = (array) $_POST + (array) $data;
			$valid = $this->model->validate($data);
		
			if ( ! $valid->check())
			{
				$this->template->errors = $valid->errors('registration');
			}
			else
			{
				if ($data['id'])
				{
					$data['id'] = $this->model->update($data, $_FILES);
					$action = self::UPDATE;
				}
				else
				{
					$data['id'] = $this->model->insert($data, $_FILES);
					$action = self::INSERT;
				}
				$this->_log_activity($data['id'], $data['fullname'], $action, $data);
				
				Request::current()->redirect($this->_index_action);
			}
		}
		
		$this->template->content->data = $data;

	}

	public function action_export()
	{
		$this->auto_render = FALSE;
//		$this->response->headers('Content-Type', 'application/vnd.ms-excel');
		$this->response->headers('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
		
		$page     = Arr::get($_GET, 'page', 1);
		$order_by = Arr::get($_GET, 'order_by', 'registration.regdate');
		$sort     = Arr::get($_GET, 'sort', 'DESC');
		$status   = Arr::get($_GET, 'status', -1);
		
		$data = $this->model->fetch_all(array(
			'order_by' => $order_by,
			'sort'     => $sort,
			'status'   => $status,
			'text'     => $text,
		));
		
//		$this->response->headers('Content-Type', 'text/html; charset=utf-8');
//		$this->response->headers('Content-Type', 'text/csv; charset=utf-8');
		$this->response->headers('Content-Type', 'application/vnd.ms-excel');
		$filename = "Equ_registros_".time().".xls";
		$this->response->headers('Content-Disposition', 'attachment; filename='.$filename);

		$content = '<table><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha registro</th></tr>';
		foreach($data as $dat)
			$content .= '<tr><td>'.$dat['id'].'</td><td>'.$dat['fullname'].'</td><td>'.$dat['email'].'</td><td>'.Timestamp::format($dat['regdate']).'</td></tr>';
		$content .= '</table>';

		$this->response->body($content);
	}

}
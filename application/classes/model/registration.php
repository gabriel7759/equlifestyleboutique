<?php defined('SYSPATH') or die('No direct script access.');

class Model_Registration extends Model {

	public function fetch_all($params)
	{
		$sql   = "";
		$limit = "";
		$parameters = array();
		
		if ( ! in_array($params['order_by'], array('registration.regdate')) )
			throw new Kohana_Exception('"'.$params['order_by']. '" is an invalid column for sorting results.');
			
		if ( ! in_array($params['sort'], array('ASC', 'DESC', 'RAND()')) )
			throw new Kohana_Exception('"sort" param must be either ASC, DESC or RAND(). "'.$params['sort'].'" given.');
		
		if (ctype_digit( (string) $params['status']))
		{
			$sql .= " AND registration.status = :status";
			$parameters[':status'] = $params['status'];
		}
		if ( ! empty($params['text']))
		{
			$sql .= " AND registration.fullname LIKE :text";
			$parameters[':text'] = '%'.$params['text'].'%';
		}
		if (ctype_digit( (string) $params['limit']) AND ctype_digit( (string) $params['offset']))
		{
			$limit = "LIMIT :offset, :limit";
			$parameters[':offset'] = $params['offset'];
			$parameters[':limit']  = $params['limit'];
		}
		
		return DB::query(Database::SELECT, "
				SELECT SQL_CALC_FOUND_ROWS 
					registration.id,
					registration.fullname,
					registration.fullname as name,
					registration.email,
					registration.regdate,
					registration.log_id
				FROM 
					registration
				WHERE 
					registration.is_deleted = 0
					".$sql."
				ORDER BY ".$params['order_by']." ".$params['sort']."
				".$limit."
			")
			->parameters($parameters)
			->execute();
	}

	public function fetch($params)
	{
		$sql = "";
		$parameters = array();

		if (ctype_digit( (string) $params['id']))
		{
			$sql .= " AND registration.id = :id";
			$parameters[':id'] = $params['id'];
		}
		
		$data = DB::query(Database::SELECT, "
				SELECT 
					registration.id,
					registration.fullname,
					registration.fullname as name,
					registration.email,
					registration.regdate,
					registration.log_id
				FROM 
					registration
				WHERE 
					registration.is_deleted = 0 
					".$sql."
				LIMIT 1
			")
			->parameters($parameters)
			->execute()
			->current();
		
		if ($data)
		{
			$log  = Model::factory('Sys_Log_Activity')->last_modified($data['log_id']);
			$data = (array) $data + (array) $log;
		}
		
		return $data;
	}


	public function check($email)
	{
		$check = DB::query(Database::SELECT, "
				SELECT id FROM registration WHERE email = :email AND is_deleted = 0 ORDER BY id ASC LIMIT 0,1
			")
			->parameters(array(
				':email' => $email,
			))
			->execute()
			->current();
		
		return $check;
	}

	public function insert($data)
	{
		$exists = $this->check($data['email']);
		if(!$exists){
			if(strlen($data['regdate'])==10){
				$date = explode("-", $data['regdate']);
				$data['regdate'] = mktime(0,0,0,$date[1],$date[2],$date[0]);
			} else if(strlen($data['regdate'])>10){
				$parts = explode(" ", $data['regdate']);
				$date = explode("-", $parts[0]);
				$time = explode(":", $parts[1]);
				$data['regdate'] = mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
			}
	
			list($id) = DB::query(Database::INSERT, "
					INSERT INTO registration (fullname, email, regdate, is_deleted, log_id)
					VALUES (:fullname, :email, :regdate, 0, 0)
				")
				->parameters(array(
					':fullname' => $data['fullname'],
					':email' => $data['email'],
					':regdate' => $data['regdate'],
				))
				->execute();
		} else {
			$id = $exists['id'];
		}
		
		return $id;
	}

	public function update($data)
	{
		$date = explode("-", $data['regdate']);
		$data['regdate'] = mktime(0,0,0,$date[1],$date[2],$date[0]);

		DB::query(Database::UPDATE, "
				UPDATE registration 
				SET
					fullname = :fullname,
					email = :email,
					regdate = :regdate,
				WHERE id = :id
			")
			->parameters(array(
				':fullname' => $data['fullname'],
				':email' => $data['email'],
				':regdate' => $data['regdate'],
				':id' => $data['id'],
			))
			->execute();

		return $data['id'];
	}


	public function validate($data)
	{
		$data = (array) $data;
		$data['id'] = (int) $data['id'];
		
		$data = Validation::factory($data)
			->rule('fullname', 'not_empty');
		
		return $data;
	}

}
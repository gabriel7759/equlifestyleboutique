<?php defined('SYSPATH') or die('No direct script access.');

class Model_Content extends Model {
	
	public function fetch_all()
	{
		$id_parent = NULL;
		
		$query = DB::query(Database::SELECT, "
				SELECT content.id, content.title, sys_lookup.name AS status, IF(content.status=0, 'inactive', '') AS mode
				FROM content
				LEFT JOIN sys_lookup ON sys_lookup.code = content.status AND sys_lookup.type = 'status'
				WHERE content.is_deleted = 0 
				ORDER BY content.position ASC
			")
			->bind(':parent_id', $id_parent);
		
		$pages = $query->execute()->as_array();
	
		for ($i=0; $i<count($pages); $i++)
		{
			$id_parent = $pages[$i]['id'];
			$pages[$i]['pages'] = $query->execute()->as_array();
			
			for ($j=0; $j<count($pages[$i]['pages']); $j++)
			{
				$id_parent = $pages[$i]['pages'][$j]['id'];
				$pages[$i]['pages'][$j]['pages'] = $query->execute()->as_array();
			}
		}
		return $pages;
	}
	
	public function fetch($params)
	{
		$sql = "";
		$parameters = array();

		if (ctype_digit( (string) $params['id']))
		{
			$sql .= " AND content.id = :id";
			$parameters[':id'] = $params['id'];
		}
		
		$data = DB::query(Database::SELECT, "
				SELECT content.id,
					content.title,
					content.title AS name,
					content.slug,
					content.content,
					content.status, 
					content.log_id
				FROM content
				WHERE content.is_deleted = 0 ".$sql."
			")
			->parameters($parameters)
			->execute()
			->current();
		
		if ($data)
		{
			$log = Model::factory('Sys_Log_Activity')->last_modified($data['log_id']);
			$data = (array) $data + (array) $log;
		}
		
		return $data;
	}
	
	public function insert($data, $files)
	{
		list($id) = DB::query(Database::INSERT, "
				INSERT INTO content (title, slug, content, position, status)
				VALUES (:title, :slug, :content, :position, :status)
			")
			->parameters(array(
				':title'         => $data['title'],
				':slug'          => $data['slug'],
				':content'       => $data['content'],
				':position'      => $this->_position(),
				':status'        => $data['status'],
			))
			->execute();
		
		$data['id'] = $id;

		return $id;
	}
	
	public function update($data, $files)
	{
		DB::query(Database::UPDATE, "
				UPDATE content 
				SET title = :title, slug = :slug, content = :content, status = :status
				WHERE id = :id
			")
			->parameters(array(
				':title'         => $data['title'],
				':slug'          => $data['slug'],
				':content'       => $data['content'],
				':status'        => $data['status'],
				':id'            => $data['id'],
			))
			->execute();
			
		return $data['id'];
	}

	private function _position()
	{
		return DB::query(Database::SELECT, "
				SELECT (position + 1) AS position 
				FROM content 
				WHERE is_deleted = 0 
				ORDER BY position DESC
				LIMIT 0,1
			")
			->execute()
			->get('position', 1);
	}
	
	public function sort($serialized)
	{
		$query = DB::query(Database::UPDATE, "UPDATE content SET position = :position WHERE id = :id")
			->bind(':position', $position)
			->bind(':id', $id);
		
		$i = $j = 1;
		foreach ($serialized['item'] as $id => $parent_id)
		{
			if ($parent_id == 'root' || $parent_id == 'null') // First level
			{
				$j = 1;
				$parent_id = NULL;
				$position = $i;
				$query->execute();
				$i++;
			}
			else // Second level
			{
				$position = $j;
				$query->execute();
				$j++;
			}
			
		}
	}
	
	public static function validate($data)
	{
		$data = (array) $data;
		$data['id'] = (int) $data['id'];
		
		$data = Validation::factory($data)
			->rule('title', 'not_empty')
			->rule('status', 'not_empty')
			->rule('status', 'in_array', array(':value', array(0, 1)));

		return $data;
	}
	
}
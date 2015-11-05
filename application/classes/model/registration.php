<?php defined('SYSPATH') or die('No direct script access.');

class Model_Registration extends Model {

	public function fetch_all($params)
	{
		$sql   = "";
		$limit = "";
		$parameters = array();
		
		if ( ! in_array($params['order_by'], array('experiences.postdate', 'experiences.status')) )
			throw new Kohana_Exception('"'.$params['order_by']. '" is an invalid column for sorting results.');
			
		if ( ! in_array($params['sort'], array('ASC', 'DESC', 'RAND()')) )
			throw new Kohana_Exception('"sort" param must be either ASC, DESC or RAND(). "'.$params['sort'].'" given.');
		
		if (ctype_digit( (string) $params['status']))
		{
			$sql .= " AND experiences.status = :status";
			$parameters[':status'] = $params['status'];
		}
		if ( ! empty($params['text']))
		{
			$sql .= " AND experiences.title LIKE :text";
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
					experiences.id,
					experiences.title,
					experiences.title as name,
					experiences.slug,
					experiences.postdate,
					experiences.author,
					sys_lookup.name AS status,
					experiences.log_id,
					IF(experiences.status=0, 'inactive', '') AS mode
				FROM 
					experiences
				LEFT JOIN 
					sys_lookup ON sys_lookup.code = experiences.status AND sys_lookup.type = 'status'
				WHERE 
					experiences.is_deleted = 0
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
			$sql .= " AND experiences.id = :id";
			$parameters[':id'] = $params['id'];
		}
		
		$data = DB::query(Database::SELECT, "
				SELECT 
					experiences.id,
					experiences.title,
					experiences.title as name,
					experiences.slug,
					experiences.postdate,
					experiences.intro,
					experiences.coverimage,
					experiences.author,
					experiences.log_id
				FROM 
					experiences
				WHERE 
					experiences.is_deleted = 0 
					".$sql."
				LIMIT 1
			")
			->parameters($parameters)
			->execute()
			->current();
		
		if ($data)
		{
			$data['options'] = DB::query(Database::SELECT, "
				SELECT 
					experiences_content.id,
					experiences_content.ctype,
					experiences_content.ctype as type,
					experiences_content.title,
					experiences_content.content,
					experiences_content.picture1,
					experiences_content.picture2,
					experiences_content.picture3,
					experiences_content.video
				FROM 
					experiences_content
				WHERE 
					experiences_content.id_experience = :id_experience
				ORDER BY
					experiences_content.position ASC
			")
			->parameters(array(':id_experience'=>$data['id']))
			->execute()
			->as_array();
			$log  = Model::factory('Sys_Log_Activity')->last_modified($data['log_id']);
			$data = (array) $data + (array) $log;
		}
		
		return $data;
	}

	public function fetch_by_url($year, $month, $slug)
	{
		$sql = "";
		$parameters = array();

		if (ctype_digit( (string) $params['id']))
		{
			$sql .= " AND experiences.slug = :slug";
			$parameters[':slug'] = $slug;
		}
		
		$data = DB::query(Database::SELECT, "
				SELECT 
					experiences.id,
					experiences.title,
					experiences.title as name,
					experiences.slug,
					experiences.postdate,
					experiences.intro,
					experiences.coverimage,
					experiences.author,
					experiences.log_id
				FROM 
					experiences
				WHERE 
					experiences.is_deleted = 0 
					".$sql."
				LIMIT 1
			")
			->parameters($parameters)
			->execute()
			->current();
		
		if ($data)
		{
			$data['options'] = DB::query(Database::SELECT, "
				SELECT 
					experiences_content.id,
					experiences_content.ctype,
					experiences_content.ctype as type,
					experiences_content.title,
					experiences_content.content,
					experiences_content.picture1,
					experiences_content.picture2,
					experiences_content.picture3,
					experiences_content.video
				FROM 
					experiences_content
				WHERE 
					experiences_content.id_experience = :id_experience
				ORDER BY
					experiences_content.position ASC
			")
			->parameters(array(':id_experience'=>$data['id']))
			->execute()
			->as_array();
			$log  = Model::factory('Sys_Log_Activity')->last_modified($data['log_id']);
			$data = (array) $data + (array) $log;
		}
		
		return $data;
	}
	
	public function fetch_active()
	{
		$data = DB::query(Database::SELECT, "
				SELECT 
					experiences.id,
					experiences.title,
					experiences.title as name,
					experiences.slug,
					experiences.postdate,
					experiences.intro,
					experiences.coverimage,
					experiences.author
				FROM 
					experiences
				WHERE 
					experiences.is_deleted = 0 
					AND experiences.status = 1
				ORDER BY
					experiences.postdate DESC
			")
			->execute()
			->as_array();
		return $data;
	}


	public function insert($data, $files)
	{

		list($id) = DB::query(Database::INSERT, "
				INSERT INTO experiences (title, slug, postdate, author, intro, status, is_deleted, log_id)
				VALUES (:title, :slug, :postdate, :author, :intro, :status, 0, 0)
			")
			->parameters(array(
				':title' => $data['title'],
				':slug' => $data['slug'],
				':postdate' => $data['postdate'],
				':author' => $data['author'],
				':intro' => $data['intro'],
				':status' => $data['status'],
			))
			->execute();
		
		$this->_upload_media($id, $files);

		return $id;
	}

	public function update($data, $files)
	{

		DB::query(Database::UPDATE, "
				UPDATE experiences 
				SET
					title = :title,
					slug = :slug,
					postdate = :postdate,
					author = :author,
					intro = :intro,
					status = :status
				WHERE id = :id
			")
			->parameters(array(
				':title' => $data['title'],
				':slug' => $data['slug'],
				':postdate' => $data['postdate'],
				':author' => $data['author'],
				':intro' => $data['intro'],
				':status' => $data['status'],
				':id' => $data['id'],
			))
			->execute();

		$this->_upload_media($data['id'], $files);

		$this->_save_parts($data);


		return $data['id'];
	}

	protected function _upload_media($id, $files)
	{
		$media = array(
			'coverimage' => 'assets/files/experiences/cover',
		);
		
		foreach ($media as $asset => $path)
		{	
			$myfile = Upload::save($_FILES[$asset], NULL, DOCROOT.$path);
			if ($myfile !== FALSE)
			{
				$myfile = basename($myfile);
				DB::query(Database::UPDATE, "UPDATE experiences SET $asset = :asset WHERE id = :id")
					->parameters(array(
						':asset' => $myfile, 
						':id'    => $id, 
					))
					->execute();
			}
			
			if ($data[$asset.'_del'])
			{
				DB::query(Database::UPDATE, "UPDATE experiences SET $asset = '' WHERE id = :id")->parameters(array(':id' => $id))->execute();
			}
		}
		
		return TRUE;
	}

	protected function _save_parts($data)
	{
		DB::query(Database::DELETE, "DELETE FROM experiences_content WHERE id_experience = :id")->parameters(array(':id' => $data['id']))->execute();
		$parts = array();
		for($i=1; $i<=$data['num_options']; $i++){
			if(isset($data['type_'.$i])){
				$cont = array(
					"type" => $data['type_'.$i],
					"title" => '',
					"content" => '',
					"picture_1" => '',
					"picture_2" => '',
					"picture_3" => '',
					"video" => '',
					"position" => $data['position_'.$i],
				);
				
				if($data['type_'.$i] == 0){
					$cont['title'] = $data['subtitle_'.$i];
					$cont['content'] = $data['content_'.$i];
				} else if($data['type_'.$i] == 1){
					$pics = $this->_upload_part_media($i, $files, $data);
					
					for($j=0; $j<count($pics); $j++){
						$cont['picture_'.($j+1)] = $pics[$j];
					}
				} else if($data['type_'.$i] == 2){
					$cont['video'] = $data['video_'.$i];
				}
			
				$parts[] = $cont;
			}
		}
		foreach($parts as $prt){
			$params = array(
					':id_experience'=>$data['id'],
					':ctype'=>$prt['type'],
					':title'=>$prt['title'],
					':content'=>$prt['content'],
					':picture1'=>$prt['picture_1'],
					':picture2'=>$prt['picture_2'],
					':picture3'=>$prt['picture_3'],
					':video'=>$prt['video'],
					':position'=>$prt['position'],
				);
			$tmp = DB::query(Database::INSERT, "
					INSERT INTO experiences_content (id_experience, ctype, title, content, picture1, picture2, picture3, video, position)
					VALUES (:id_experience, :ctype, :title, :content, :picture1, :picture2, :picture3, :video, :position)
				")
				->parameters($params)
				->execute();
		}
	}

	protected function _upload_part_media($id, $files, $data)
	{
		$path = 'assets/files/experiences/pictures';
		$files = array();
		for($i=1; $i<=3; $i++)
		{	
		
			$myfile = Upload::save($_FILES['picturef'.$i.'_'.$id], NULL, DOCROOT.$path);
			if ($myfile !== FALSE)
			{
				$myfile = basename($myfile);
				$files[] = $myfile;
			} else if(strlen($data['picture'.$i.'_'.$id])) {
				$files[] = $data['picture'.$i.'_'.$id];
			}
		}
		
		return $files;
	}

	public function validate($data)
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
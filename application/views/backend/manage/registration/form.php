		<form name="save" action="" method="post" class="form validate" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?=$data['id']?>" />
			<div class="sidebar">
				<?php if($action_status): ?>
				<div class="sidebox">
					<h2><?=__('Status')?></h2>
					<div class="field">
						<label class="option"><input type="radio" name="status" value="1"<?php if(Arr::get($data, 'status', 1)==1): ?> checked="checked"<?php endif; ?> /> <?=__('Active')?></label>
						<label class="option"><input type="radio" name="status" value="0"<?php if(Arr::get($data, 'status', 1)==0): ?> checked="checked"<?php endif; ?> /> <?=__('Inactive')?></label>
					</div>
				</div>
				<?php endif; ?>
				<?php if($data['id']): ?>
				<div class="sidebox">
					<h2><?=__('Last modified')?></h2>
					<p class="last-modified"><strong><?=$data['log_user']?></strong><br /> <?=Timestamp::format($data['log_time'], '%d/%B/%Y %H:%M')?></p>
				</div>
				<?php endif; ?>
			</div>
			<div class="fieldset">
				<h2><?=__('General details')?></h2>
				<div class="field full">
					<label><?=__('Title')?> <span class="req">*</span></label>
					<input type="text" name="title" value="<?=$data['title']?>" class="required" title="<?=__('Ingrese el título')?>" />
				</div><br />
				<div class="field half">
					<label><?=__('Fecha')?> <span class="req">*</span></label>
					<input type="text" name="postdate" value="<?=Arr::get($data, 'postdate', date('Y-m-d'))?>" class="required date" title="<?=__('Ingrese la fecha')?>" />
				</div>
				<div class="field half">
					<label><?=__('Autor')?> <span class="req">*</span></label>
					<input type="text" name="author" value="<?=$data['author']?>" class="required" title="<?=__('Ingrese el autor')?>" />
				</div><br />
				<div class="field full">
					<label><?=__('Intro')?> <span class="req">*</span></label>
					<textarea name="intro"><?=$data['intro']?></textarea>
				</div><br />
				<div class="field full">
					<label><?=__('Portada')?> <span class="req">*</span> <span>(JPG 270 x 270)</span></label>
					<div class="file">
						<div class="fileinput">
							<input type="text" name="coverimage_tmp" value="" disabled="disabled">
							<div><input type="file" name="coverimage" /></div>
						</div>
						<div class="fname"<?php if($data['coverimage']!=""): ?>style="display:block;"<?php endif; ?>>
							<a href="../assets/files/experiences/cover/<?=$data['coverimage']?>" target="_blank"><img src="../assets/files/experiences/cover/<?=$data['coverimage']?>" height="75" alt="" /></a>
							<a href="#" class="del">Delete</a>
							<a href="../assets/files/experiences/cover/<?=$data['coverimage']?>" target="_blank" class="iname"><?=$data['coverimage']?></a>
							<input type="checkbox" name="coverimage_del" value="1" />
						</div>
					</div>
				</div>
				<br />
				<div class="field">
					<label><?=__('Contenidos')?></label>
					<ul id="optionslist" class="optionslist">
			<?php
				$options = Arr::get($data, 'options', array());
				if(count($options)==0){
					$options = array(
						array(
							"id" => 0,
							"type" => 0,
							"title" => '',
							"content" => '',
							"picture_1" => '',
							"picture_2" => '',
							"picture_3" => '',
							"video" => '',
						),
					);
				}
				$i=1;
				foreach($options as  $opt):
			?>
						<li><input type="hidden" name="position_<?=$i?>" value="<?=$i?>" class="setposition">
							<input type="hidden" name="type_<?=$i?>" value="<?=$opt['type']?>" />
							<a href="#" class="delopt">remove</a>
							<a href="#" class="sortopt">sort</a>
					<?php
						switch($opt['type']):
							case 0:
					?>
							<div class="title">
								<label><?=__('Subtítulo')?></label><br />
								<input type="text" name="subtitle_<?=$i?>" value="<?=$opt['title']?>" class="required" title="<?=__('Ingrese el subtitulo')?>" autocomplete="off" />
							</div>
							<div class="description" id="cont_description_<?=$i?>">
								<label><?=__('Contenido')?></label><br />
								<textarea name="content_<?=$i?>" class="ckeditor"><?=$opt['content']?></textarea>
							</div>
					<?php
								break;
							case 1:
					?>
							<div class="picture">
								<label><?=__('Picture 1')?> <span class="req">*</span> <span>(JPG 1920 x 580 minimo)</span></label>
								<div class="file">
									<div class="fileinput">
										<input type="text" name="picture1_<?=$i?>_tmp" value="" disabled="disabled">
										<div><input type="file" name="picturef1_<?=$i?>" /></div>
									</div>
									<div class="fname"<?php if($opt['picture1']!=""): ?>style="display:block;"<?php endif; ?>>
										<a href="../assets/files/experiences/pictures/<?=$opt['picture1']?>" target="_blank"><img src="../assets/files/experiences/pictures/<?=$opt['picture1']?>" height="65" alt="" /></a>
										<a href="#" class="del">Delete</a>
										<a href="../assets/files/experiences/pictures/<?=$opt['picture1']?>" target="_blank" class="iname"><?=$opt['picture1']?></a>
										<input type="hidden" name="picture1_<?=$i?>" value="<?=$opt['picture1']?>" />
										<input type="checkbox" name="picture1_<?=$i?>_del" value="1" />
									</div>
								</div>
							</div><br />
							<div class="picture">
								<label><?=__('Picture 2')?> <span class="req">*</span> <span>(JPG 1920 x 580 minimo)</span></label>
								<div class="file">
									<div class="fileinput">
										<input type="text" name="picture2_<?=$i?>_tmp" value="" disabled="disabled">
										<div><input type="file" name="picturef2_<?=$i?>" /></div>
									</div>
									<div class="fname"<?php if($opt['picture2']!=""): ?>style="display:block;"<?php endif; ?>>
										<a href="../assets/files/experiences/pictures/<?=$opt['picture2']?>" target="_blank"><img src="../assets/files/experiences/pictures/<?=$opt['picture2']?>" height="65" alt="" /></a>
										<a href="#" class="del">Delete</a>
										<a href="../assets/files/experiences/pictures/<?=$opt['picture2']?>" target="_blank" class="iname"><?=$opt['picture2']?></a>
										<input type="hidden" name="picture2_<?=$i?>" value="<?=$opt['picture2']?>" />
										<input type="checkbox" name="picture2_<?=$i?>_del" value="1" />
									</div>
								</div>
							</div><br />
							<div class="picture">
								<label><?=__('Picture 3')?> <span class="req">*</span> <span>(JPG 1920 x 580 minimo)</span></label>
								<div class="file">
									<div class="fileinput">
										<input type="text" name="picture3_<?=$i?>_tmp" value="" disabled="disabled">
										<div><input type="file" name="picturef3_<?=$i?>" /></div>
									</div>
									<div class="fname"<?php if($opt['picture3']!=""): ?>style="display:block;"<?php endif; ?>>
										<a href="../assets/files/experiences/pictures/<?=$opt['picture3']?>" target="_blank"><img src="../assets/files/experiences/pictures/<?=$opt['picture3']?>" height="65" alt="" /></a>
										<a href="#" class="del">Delete</a>
										<a href="../assets/files/experiences/pictures/<?=$opt['picture3']?>" target="_blank" class="iname"><?=$opt['picture3']?></a>
										<input type="hidden" name="picture3_<?=$i?>" value="<?=$opt['picture3']?>" />
										<input type="checkbox" name="picture3_<?=$i?>_del" value="1" />
									</div>
								</div>
							</div>
					<?php
								break;
							case 2:
					?>
							<div class="title">
								<label><?=__('Video')?> <span class="req">*</span> <span>(https://www.youtube.com/watch?v=XXXXXXXXX)</span></label><br />
								<input type="text" name="video_<?=$i?>" value="<?=$opt['video']?>" class="required" title="<?=__('Ingrese el url del video')?>" autocomplete="off" />
							</div>
					<?php
								break;
						endswitch;
					?>
						</li>
			<?php
					$i++;
				endforeach;
			?>
					</ul>
				</div><br />
				<div class="buttons" id="addoptioncont">
					<input type="hidden" name="num_options" id="num_options" value="<?=count($options)?>" autocomplete="off" />
					<button type="button" class="button" id="addon_addoption_content" style="margin-right: 20px;"><?=__('Add content block')?></button>
					<button type="button" class="button" id="addon_addoption_picture" style="margin-right: 20px;"><?=__('Add picture block')?></button>
					<button type="button" class="button" id="addon_addoption_video" style="margin-right: 20px;"><?=__('Add video block')?></button>
				</div>
				<br />	
				<div class="buttons">
					<button type="submit" class="button"><?=__('Save')?></button>
					<button type="button" class="button cancel"><?=__('Cancel')?></button>
				</div>
			</div>
		</form>

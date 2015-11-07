		<form name="save" action="" method="post" class="form validate" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?=$data['id']?>" />
			<div class="sidebar">
				<div class="sidebox">
					<?php if($action_status): ?>
					<h2>Estatus</h2>
					<div class="field">
						<label class="option"><input type="radio" name="status" value="1"<?php if(Arr::get($data, 'status', 1)==1): ?> checked="checked"<?php endif; ?> /> Activo</label>
						<label class="option"><input type="radio" name="status" value="0"<?php if(Arr::get($data, 'status', 1)==0): ?> checked="checked"<?php endif; ?> /> Inactivo</label>
					</div>
					<?php endif; ?>
				</div>
				<?php if($data['id']): ?>
				<div class="sidebox">
					<h2>&Uacute;ltima modificaci&oacute;n</h2>
					<p class="last-modified"><strong><?=$data['log_user']?></strong><br /> <?=Timestamp::format($data['log_time'], '%d de %B del %Y a las %H:%M')?></p>
				</div>
				<?php endif; ?>
			</div>
			<div class="fieldset">
				<h2>Datos generales</h2>
				<div class="field full">
					<label>T&iacute;tulo <span class="req">*</span></label>
					<input type="text" name="title" value="<?=$data['title']?>" class="required" title="Escriba el t&iacute;tulo" />
				</div>
				<div class="field full">
					<label>Contenido</label>
					<textarea name="content" cols="50" rows="8" class="ckeditor"><?=$data['content']?></textarea>
				</div>
				<br />
				<div class="buttons">
					<button type="submit" class="button">Guardar</button>
					<button type="button" class="button cancel">Cancelar</button>
					<?php if($data['id'] AND $action_delete): ?><button type="button" class="button delete">Eliminar</button><?php endif; ?>
				</div>
			</div>
		</form>

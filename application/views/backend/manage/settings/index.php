		<?php if(isset($_GET['done'])): ?><div class="message info">Settings have been updated.</div><?php endif; ?>
		<form name="save" action="" method="post" class="form validate" enctype="multipart/form-data">
			<div class="sidebar">
				<?php if($data['id']): ?>
				<div class="sidebox">
					<h2>Last modified</h2>
					<p class="last-modified"><strong><?=$data['log_user']?></strong><br /> <?=Timestamp::format($data['log_time'], '%d/%B/%Y %H:%M')?></p>
				</div>
				<?php endif; ?>
			</div>
			<div class="fieldset">
				<h2>Website</h2>
				<!--
				<div class="field">
					<label><?=__('Contact email')?> <span class="req">*</span></label>
					<input type="text" name="email_contact" value="<?=$data['email_contact']?>" class="required" title="<?=__('Please enter the contact email')?>" />
				</div><br />
				-->
				<div class="field full">
					<label>Google analytics</label>
					<textarea name="google_analytics" title="<?=__('Please enter the google analytics account')?>"><?=$data['google_analytics']?></textarea>
				</div><br />
				<div class="field full">
					<label>Keywords</label>
					<textarea name="keywords" cols="50" rows="2"><?=$data['keywords']?></textarea>
				</div><br />
				<div class="field full">
					<label>Descripción para buscadores</label>
					<textarea name="description" cols="50" rows="2"><?=$data['description']?></textarea>
				</div><br />
				<h2>Facebook share</h2>
				<div class="field">
					<label>Name/Title <span class="req">*</span></label>
					<input type="text" name="fb_name" value="<?=$data['fb_name']?>" class="required" title="Please enter the facebook name/title" />
				</div>
				<div class="field">
					<label>Caption <span class="req">*</span></label>
					<input type="text" name="fb_caption" value="<?=$data['fb_caption']?>" />
				</div>
				<div class="field full">
					<label>Description <span class="req">*</span></label>
					<textarea name="fb_description" cols="50" rows="2" class="required" title="Please enter the facebook description"><?=$data['fb_description']?></textarea>
				</div>
				<div class="field">
					<label>Link <span class="req">*</span></label>
					<input type="text" name="fb_link" value="<?=$data['fb_link']?>" class="required" title="Please enter the facebook link" />
				</div>
				<div class="field full">
					<label>Image <span class="req">*</span> <span>(JPG 200 x 200)</span></label>
					<div class="file">
						<div class="fileinput">
							<input type="text" name="fb_image_tmp" value="" disabled="disabled">
							<div><input type="file" name="fb_image" /></div>
						</div>
						<div class="fname"<?php if($data['fb_image']!=""): ?>style="display:block;"<?php endif; ?>>
							<a href="../assets/files/facebook/<?=$data['fb_image']?>" target="_blank"><img src="../assets/files/facebook/<?=$data['fb_image']?>" height="75" alt="" /></a>
							<a href="#" class="del">Delete</a>
							<a href="../assets/files/facebook/<?=$data['fb_image']?>" target="_blank" class="iname"><?=$data['fb_image']?></a>
							<input type="checkbox" name="fb_image_del" value="1" />
						</div>
					</div>
				</div><br />
				<div class="buttons">
					<button type="submit" class="button"><?=__('Update')?></button>
				</div>
			</div>
		</form>

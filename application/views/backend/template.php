<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<base href="<?=URL::base(TRUE)?>admin/" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="../assets/css/backend.css" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="../assets/uploadify/uploadify.css" />
	<script type="text/javascript" src="../assets/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<?php if($user AND $menu): ?>
	<!--<script type="text/javascript" src="../assets/ckeditor/ckeditor.js"></script>-->
	<script type="text/javascript" src="../assets/tinymce/jquery.tinymce.min.js"></script>
	<?php endif; ?>
	<script type="text/javascript" src="../assets/js/jquery.ui.nestedSortable.js"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="../assets/js/backend.js"></script>
	<title><?=$title?> - <?=__('Admin interface')?> - <?=$sitename?></title>
</head>
<body>
<div id="wrapper" class="navbar">
	<div id="header" class="clearfix">
		<h2><?=__('Administration interface for')?> <strong><?=$sitename?></strong> <span>(<a href="/" target="_blank"><?=__('view website')?></a>)</span></h2>
		<?php if($user): ?>
		<div class="identity">
			<a href=""><?=$user['email']?></a>
			<ul>
				<li><strong><?=$user['name']?></strong><br /><?=$user['role']?></li>
				<li><a href="start/session/logout" class="logout"><?=__('Logout')?></a></li>
			</ul>
		</div>
		<?php endif; ?>
	</div>
	<?php if($user AND $menu): ?>
	<ul id="menu" class="clearfix">
		<li><a href="start/overview/index"><?=__('Start')?></a></li>
		<?php foreach($menu as $level1): ?>
		<?php if(count($level1['submenu'])): ?>
		<li><a href="<?=$level1['directory']?>/<?=$level1['submenu'][0]['directory']?>/index" class="<?=$level1['selected']?>"><?=__($level1['name'])?><?php if(count($level1['submenu'])): ?> <span></span><?php endif; ?></a>
			<ul>
				<?php foreach($level1['submenu'] as $level2): ?>
				<li><a href="<?=$level1['directory']?>/<?=$level2['directory']?>/index"><?=__($level2['name'])?></a><span><?=$level2['description']?></span></li>
				<?php endforeach; ?>
			</ul>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<div id="content" class="clearfix">
		<div id="content-title" class="clearfix">
			<h1><?=$title?></h1>
		</div>
		<?php if($errors): ?>
		<div class="message error">
			<?=__('The following errors ocurred')?>:
			<ul>
				<?php foreach($errors as $error): ?>
				<li><?=$error?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	<?php endif; ?>
		<?=$content?>
	<?php if($user AND $menu): ?>
	</div>
	<?php endif; ?>
	<div id="footer" class="clearfix">
		<p class="help">
		</p>
	</div>
</div>

<div id="modal-overlay"></div>
<div id="modal-confirm" data-action="" data-itemid="" data-status="">
	<div class="box">
		<div class="box-header clearfix">
			<h2><?=__('Warning')?></h2>
			<a href="#" class="close"><?=__('Close')?></a>
		</div>
		<div class="box-body">
			<p>
				<span class="ellipsis"><?=__('You are about to delete')?> <strong>"Item name"</strong></span>
				<?=__('Are you sure you want to continue?')?>
			</p>
			<div class="buttons">
				<button type="button" class="button small accept"><?=__('OK')?></button>
				<button type="button" class="button small cancel"><?=__('Cancel')?></button>
			</div>
		</div>
	</div>
</div>
<div id="tooltip"></div>

</body>
</html>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<base href="<?=URL::base(TRUE)?>" />
	<title><?php echo $page['title']; ?> Cuantro Vientos</title>
	<meta name="viewport" content="width=1280" />
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/frontend.css" type="text/css" />
	<link rel="shortcut icon" href="img/favicon.ico"  />
	<script type="text/javascript" src="assets/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="assets/js/frontend.js"></script>
	<meta name="keywords" content="<?=$keywords?>" />
	<meta name="description" content="<?=$description?>" />
	<meta property="og:title" content="<?=$fb_name?>" />
	<meta property="og:site_name" content="<?=$sitename?>" />
	<meta property="og:description" content="<?=$fb_desc?>" />
	<meta property="og:url" content="<?=$siteurl?>" />
	<meta property="og:image" content="<?=$fb_image?>" />
	<meta property="og:type" content="<?=$fb_type?>" />
</head>

<body>
<div id="fb-root"></div>
<header>
	<section class="logo"><a href="/"><img src="assets/img/frontend/logo.png" width="300"></a></section>
	<section class="menu">
		<a href="nosotros">Nosotros</a>
		<span></span>
		<a href="experiencias">Experiencias</a>
		<span></span>
		<a href="blog">Blog</a>
		<span></span>
		<a href="contacto">Contacto</a>
		<ul class="social">
			<li><a href="#" class="nstgrm">Instagram</a></li>
			<li><a href="#" class="fb">Facebook</a></li>
			<li><a href="#" class="vmo">Vimeo</a></li>
		</ul>
	</section>
</header>
<?=$content?>
<?=$analytics?>
</body>
</html>
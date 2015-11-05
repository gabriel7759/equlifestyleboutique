<!doctype html>
<html lang="en">
<head>

<meta charset="utf-8">

<!-- description/content -->

	<meta name="keywords" content="<?=$keywords?>" />
	<meta name="description" content="<?=$description?>" />
	<meta property="og:title" content="<?=$fb_name?>" />
	<meta property="og:site_name" content="<?=$sitename?>" />
	<meta property="og:description" content="<?=$fb_desc?>" />
	<meta property="og:url" content="<?=$siteurl?>" />
	<meta property="og:image" content="<?=$fb_image?>" />
	<meta property="og:type" content="<?=$fb_type?>" />


<!-- viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

<!-- meta - webapp -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="application-name" content="Equ Lifestyle">
<meta name="apple-mobile-web-app-title" content="Equ Lifestyle">
<meta name="theme-color" content="#292b37">

<!-- phone -->
<meta name="format-detection" content="telephone=no">



<!-- icons -->
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" sizes="228x228" href="assets/img/icons/eqlife_icon.png">


<!-- CSS -->
<link rel="stylesheet" type="text/css" href="assets/css/frontend.css">


<!-- JS -->
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/countdown.js"></script>
<script type="text/javascript" src="assets/js/frontend.js"></script>



<!-- ie8 -->

<!--[if IE]>
<script type="text/javascript">
var e = ("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(',');
for (var i=0; i<e.length; i++) {
document.createElement(e[i]);
}
</script>
<style type="text/css">
.gradient {filter: none;}
</style>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie8.css" />
<![endif]-->


	<base href="<?=URL::base(TRUE)?>" />
	<title><?php echo $page['title']; ?> EQU Lifestyle</title>

</head>
<body>

<main>
<?=$content?>
    <footer>
    	<article class="inner">
        	<a href="#">PRIVACY</a>
            <p>© Copyright 2015 EQULifestyle. All rights reserved.</p>
        </article>
    </footer>
</main>
<?=$analytics?>
</body>
</html>

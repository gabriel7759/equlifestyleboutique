<section class="full image" style="background-image: url(assets/files/images/East-Greenland-Feature.jpg); background-position: 50% 50%; background-size: cover; background-repeat: no-repeat;">
	<h2>Barrancas del Cobre</h2>
	<h3>expedición 2015</h3>
</section>
<section class="menu submenu">
	<a href="#">Naturaleza oculta</a>
	<span></span>
	<a href="#">Ciudades inesperadas</a>
	<span></span>
	<a href="#">Experiencias inolvidables</a>
</section>
<section>
<ul class="listing">
<?php
	foreach($experiences as $exps){
		$date = explode('-', $exps['postdate']);
?>
	<li><img src="assets/files/experiences/cover/<?=$exps['coverimage']?>" width="270" height="270" /><a href="experiencias/<?=$date[0]?>/<?=$date[1]?>/<?=$exps['slug']?>"><span><?=$exps['title']?></span><br />Por: <?=$exps['author']?></a></li>
<?php
}
?>
<!--
	<li><img src="assets/files/images/menu2.jpg" width="270" height="270" /><a href="experiencias/2015/10/diario-oaxaca"><span>Mountains</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu3.jpg" width="270" height="270" /><a href="experiencias/2015/10/diario-oaxaca"><span>Hiking morning</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu4.jpg" width="270" height="270" /><a href="experiencias/2015/10/diario-oaxaca"><span>Living in paradise</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu1.jpg" width="270" height="270" /><a href="experiencias/2015/10/diario-oaxaca"><span>Diario Oaxaca</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu2.jpg" width="270" height="270" /><a href="experiencias/2015/10/diario-oaxaca"><span>Mountains</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu3.jpg" width="270" height="270" /><a href="#"><span>Hiking morning</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu4.jpg" width="270" height="270" /><a href="#"><span>Living in paradise</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu1.jpg" width="270" height="270" /><a href="#"><span>Diario Oaxaca</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu2.jpg" width="270" height="270" /><a href="#"><span>Mountains</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu3.jpg" width="270" height="270" /><a href="#"><span>Hiking morning</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu4.jpg" width="270" height="270" /><a href="#"><span>Living in paradise</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu1.jpg" width="270" height="270" /><a href="#"><span>Diario Oaxaca</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu2.jpg" width="270" height="270" /><a href="#"><span>Mountains</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu3.jpg" width="270" height="270" /><a href="#"><span>Hiking morning</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu4.jpg" width="270" height="270" /><a href="#"><span>Living in paradise</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu3.jpg" width="270" height="270" /><a href="#"><span>Hiking morning</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu4.jpg" width="270" height="270" /><a href="#"><span>Living in paradise</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu1.jpg" width="270" height="270" /><a href="#"><span>Diario Oaxaca</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu2.jpg" width="270" height="270" /><a href="#"><span>Mountains</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu3.jpg" width="270" height="270" /><a href="#"><span>Hiking morning</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu4.jpg" width="270" height="270" /><a href="#"><span>Living in paradise</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu1.jpg" width="270" height="270" /><a href="#"><span>Diario Oaxaca</span><br />Por: Miguel Rivera</a></li>
	<li><img src="assets/files/images/menu2.jpg" width="270" height="270" /><a href="#"><span>Mountains</span><br />Por: Miguel Rivera</a></li>
-->
</ul>
</section>
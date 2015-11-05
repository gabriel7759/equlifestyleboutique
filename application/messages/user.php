<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'name' => array(
		'not_empty' => utf8_encode('Vennligst skriv inn navn.'),
	),
	'email' => array(
		'not_empty' => utf8_encode('Vennligst skriv inn en gyldig epostadress.'),
		'email' => utf8_encode('Feltet "e-post" m� inneholde en gyldig epostadresse.'),
		'Model_User::unique_email' => utf8_encode('E-postadressen er allerede registrert. Vennligst logg inn til h�yre.'),
	),
	'password' => array(
		'not_empty' => utf8_encode('Vennligst fyll inn passord .'),
		'min_length' => utf8_encode('Passordet m� v�re p� minst �tte tegn.'),
	),
	'confirm' => array(
		'not_empty' => utf8_encode('Feltet "bekreft passord" kan ikke v�re tomt.'),
		'matches' => utf8_encode('Feltene "passord" og "bekreft passord" m� v�re like.'),
	),
);
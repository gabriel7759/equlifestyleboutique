<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'answer' => array(
		'not_empty' => utf8_encode('Du har ikke svart p� oppgaven.'),
		//'Model_User_Answer::unique_answer' => utf8_encode('Du har brukt opp sjansene dine p� denne luken.'),
		'Model_User_Answer::unique_answer' => utf8_encode('Du har allerede svart riktig p� dagens luke.'),
		'Model_User_Answer::check_day' => utf8_encode('Denne luken er lukket.'),
	),
);
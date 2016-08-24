<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

$isLoggedIn = check_login_state();
if ($isLoggedIn !== 0) {
	header_redirect('login.php');
}

create_view("index");
?>

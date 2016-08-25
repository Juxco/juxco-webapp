<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/init.php';

if (check_login_state() === 0) {
	header_redirect('index.php');
}

create_view("register");
?>
